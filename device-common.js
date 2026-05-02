// device-common.js - الدوال المشتركة لجميع أجهزة الجسم
// يجب تعريف SYSTEM_ID و ORGANS في الصفحة قبل استدعاء initDevicePage()

async function loadReadOrgans() {
    let key = `studiedOrgans_${window.SYSTEM_ID}`;
    let stored = localStorage.getItem(key);
    return stored ? JSON.parse(stored) : [];
}
async function saveReadOrgans(organsArray) {
    let key = `studiedOrgans_${window.SYSTEM_ID}`;
    localStorage.setItem(key, JSON.stringify(organsArray));
    updateProgressUI();
    updateOrgansListUI();
}
async function markOrganAsRead(organId) {
    let readList = await loadReadOrgans();
    if (!readList.includes(organId)) {
        readList.push(organId);
        await saveReadOrgans(readList);
    }
}
async function isOrganRead(organId) {
    let list = await loadReadOrgans();
    return list.includes(organId);
}
async function updateProgressUI() {
    let list = await loadReadOrgans();
    let count = list.length;
    let percent = (count / window.ORGANS.length) * 100;
    let fill = document.getElementById('localProgressFill');
    if (fill) fill.style.width = percent + "%";
    let readSpan = document.getElementById('readCountDisplay');
    if (readSpan) readSpan.innerText = count;
    let totalSpan = document.getElementById('totalOrgansDisplay');
    if (totalSpan) totalSpan.innerText = window.ORGANS.length;
}
async function resetProgress() {
    if (confirm("⚠️ هل أنت متأكد من إعادة تعيين التقدم وجميع الملاحظات لهذا الجهاز؟")) {
        let key = `studiedOrgans_${window.SYSTEM_ID}`;
        localStorage.removeItem(key);
        for (let organ of window.ORGANS) {
            localStorage.removeItem(`note_${organ.id}`);
        }
        await saveReadOrgans([]);
        if (window.currentOrganId) selectOrganById(window.currentOrganId);
        let feedback = document.getElementById('noteFeedback');
        if (feedback) feedback.innerHTML = "<span class='text-warning'>✓ تم إعادة التعيين</span>";
        setTimeout(() => { if(feedback) feedback.innerHTML = ""; }, 2000);
        await loadAllNotesAndRender();
    }
}
function renderOrgansList() {
    let container = document.getElementById('organsListContainer');
    if (!container) return;
    container.innerHTML = '';
    window.ORGANS.forEach(organ => {
        let btn = document.createElement('button');
        btn.className = 'organ-btn';
        btn.innerText = organ.name;
        btn.onclick = () => selectOrganById(organ.id);
        container.appendChild(btn);
    });
    updateOrgansListUI();
}
async function updateOrgansListUI() {
    let btns = document.querySelectorAll('.organ-btn');
    for (let btn of btns) {
        let name = btn.innerText;
        let organ = window.ORGANS.find(o => o.name === name);
        if (organ && await isOrganRead(organ.id)) {
            btn.classList.add('read');
        } else if (organ) {
            btn.classList.remove('read');
        }
    }
}
async function selectOrganById(organId) {
    let organ = window.ORGANS.find(o => o.id === organId);
    if (!organ) return;
    window.currentOrganId = organ.id;
    document.getElementById('organTitle').innerText = organ.name;
    document.getElementById('organDesc').innerHTML = organ.details;
    await markOrganAsRead(organ.id);
    await loadNoteForCurrent();
    let infoBtn = document.querySelectorAll('.custom-pills .nav-link')[0];
    if (infoBtn && infoBtn.innerText.includes('معلومات')) switchTab('info', infoBtn);
}
// دوال الملاحظات (مع localStorage + دعم PHP)
async function saveNoteToServer(organId, noteText) {
    // حفظ محلياً أولاً
    localStorage.setItem(`note_${organId}`, noteText);
    // يمكن إضافة كود إرسال إلى PHP هنا إذا أردت
    return true;
}
async function deleteNoteFromServer(organId) {
    localStorage.removeItem(`note_${organId}`);
    return true;
}
async function fetchAllNotesFromServer() {
    let notes = [];
    for (let organ of window.ORGANS) {
        let txt = localStorage.getItem(`note_${organ.id}`);
        if (txt && txt.trim()) {
            notes.push({ organ_id: organ.id, organ_name: organ.name, note_text: txt });
        }
    }
    return notes;
}
async function loadAllNotesAndRender() {
    let notes = await fetchAllNotesFromServer();
    let container = document.getElementById('allNotesList');
    if (!container) return;
    if (notes.length === 0) {
        container.innerHTML = '<p class="text-secondary small">لا توجد ملاحظات مسجلة بعد.</p>';
        return;
    }
    container.innerHTML = '';
    for (let note of notes) {
        let div = document.createElement('div');
        div.className = 'note-item';
        div.innerHTML = `
            <div class="d-flex justify-content-between">
                <strong>🔹 ${note.organ_name}</strong>
                <button class="btn btn-sm btn-outline-danger" onclick="deleteSpecificNote('${note.organ_id}')">🗑️</button>
            </div>
            <p class="small mb-0 mt-1">${note.note_text.substring(0, 100)}${note.note_text.length > 100 ? '...' : ''}</p>
            <button class="btn btn-sm btn-outline-info mt-1" onclick="editNote('${note.organ_id}')">✏️ تعديل</button>
        `;
        container.appendChild(div);
    }
}
async function saveCurrentNote() {
    if (!window.currentOrganId) return;
    let noteText = document.getElementById('userNote').value;
    if (noteText.trim() === "") {
        document.getElementById('noteFeedback').innerHTML = "<span class='text-danger'>⚠️ لا يمكن حفظ ملاحظة فارغة</span>";
        return;
    }
    await saveNoteToServer(window.currentOrganId, noteText);
    document.getElementById('noteFeedback').innerHTML = "<span class='text-success'>✓ تم حفظ الملاحظة</span>";
    setTimeout(() => document.getElementById('noteFeedback').innerHTML = "", 2000);
    await loadAllNotesAndRender();
}
async function deleteCurrentNote() {
    if (!window.currentOrganId) return;
    if (!confirm("هل أنت متأكد من حذف ملاحظة هذا العضو؟")) return;
    await deleteNoteFromServer(window.currentOrganId);
    document.getElementById('userNote').value = "";
    document.getElementById('noteFeedback').innerHTML = "<span class='text-info'>✓ تم حذف الملاحظة</span>";
    setTimeout(() => document.getElementById('noteFeedback').innerHTML = "", 2000);
    await loadAllNotesAndRender();
}
async function deleteSpecificNote(organId) {
    if (!confirm("حذف ملاحظة هذا العضو؟")) return;
    await deleteNoteFromServer(organId);
    if (window.currentOrganId === organId) document.getElementById('userNote').value = "";
    await loadAllNotesAndRender();
    document.getElementById('noteFeedback').innerHTML = "<span class='text-info'>✓ تم الحذف</span>";
    setTimeout(() => document.getElementById('noteFeedback').innerHTML = "", 1500);
}
async function editNote(organId) {
    let noteText = localStorage.getItem(`note_${organId}`) || "";
    document.getElementById('userNote').value = noteText;
    let organ = window.ORGANS.find(o => o.id === organId);
    if (organ) {
        document.getElementById('noteOrganName').innerText = organ.name;
        window.currentOrganId = organId;
    }
    let notesTabBtn = document.querySelectorAll('.custom-pills .nav-link')[1];
    notesTabBtn.click();
}
async function loadNoteForCurrent() {
    if (window.currentOrganId) {
        let note = localStorage.getItem(`note_${window.currentOrganId}`) || "";
        document.getElementById('userNote').value = note;
        let organ = window.ORGANS.find(o => o.id === window.currentOrganId);
        document.getElementById('noteOrganName').innerText = organ ? organ.name : window.currentOrganId;
    }
}
function switchTab(tabId, el) {
    document.querySelectorAll('.pane').forEach(p => p.style.display = 'none');
    document.querySelectorAll('.custom-pills .nav-link').forEach(t => t.classList.remove('active'));
    document.getElementById(tabId + '-tab').style.display = 'block';
    el.classList.add('active');
    if (tabId === 'notes') {
        loadNoteForCurrent();
        loadAllNotesAndRender();
    }
}
async function initDevicePage() {
    // تخزين قائمة الأعضاء في localStorage للاستخدام في lab
    localStorage.setItem(`organsList_${window.SYSTEM_ID}`, JSON.stringify(window.ORGANS));
    // تخزين العدد الإجمالي
    localStorage.setItem(`totalOrgans_${window.SYSTEM_ID}`, window.ORGANS.length);
    
    renderOrgansList();
    await updateProgressUI();
    if (window.ORGANS.length) await selectOrganById(window.ORGANS[0].id);
    await loadAllNotesAndRender();
    
    document.getElementById('resetProgressBtn')?.addEventListener('click', resetProgress);
}
// ربط الدوال على window لتكون متاحة في الـ onclick
window.saveCurrentNote = saveCurrentNote;
window.deleteCurrentNote = deleteCurrentNote;
window.deleteSpecificNote = deleteSpecificNote;
window.editNote = editNote;
window.selectOrganById = selectOrganById;
window.switchTab = switchTab;
window.resetProgress = resetProgress;
