const userBtnList = document.getElementById('listBtn');
const btnId = document.getElementById('getId');
const idInput = document.getElementById('getIdInput');
const updateId = document.getElementById('updateId');
const updateInput = document.getElementById('updateInput');
const updateIdInput = document.getElementById('updateIdInput')
const contentDiv = document.getElementById("content");
const xhttp = new XMLHttpRequest();

function userList() {

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            const response = JSON.parse(this.responseText);
            let html = "<ul>";
            response.data.forEach(item => {
                html += `<li>Age: ${item.age}</li>`;
            });
            html += "</ul>";
            contentDiv.innerHTML = html;
        }
    };

    xhttp.open("GET", "http://localhost/cloud_storage/index.php/users/list", true);
    xhttp.send();
}

function getId() {
    const getId = idInput.value;

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            const response = JSON.parse(this.responseText);
            let html = "<ul>";
            html += `<li>ID: ${ response.data.id}, Age: ${ response.data.age}</li>`;
            html += "</ul>";
            contentDiv.innerHTML = html;
        }
    };

    xhttp.open("GET", `http://localhost/cloud_storage/index.php/users/get/${getId}`, true);
    xhttp.send();

    idInput.value = ''
}

function updateIdFc() {
    const updateId = updateInput.value;
    const updateIdInp = updateIdInput.value;

    xhttp.open("PUT", "http://localhost/cloud_storage/index.php/users/update", true);
    xhttp.setRequestHeader("Content-type", "application/json");
    xhttp.send(JSON.stringify({ id: updateIdInp, age: updateId }));

    updateInput.value = ''
    updateIdInput.value = ''

}

if (btnId) {
    btnId.addEventListener('click', getId);
}
if (userBtnList) {
    userBtnList.addEventListener('click', userList);
}

if (updateId) {
    updateId.addEventListener('click', updateIdFc);
}

//////////////////////////////////////////////////////admin

const adminListBtn = document.getElementById('adminListBtn');

function adminList() {
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            const response = JSON.parse(this.responseText);
            const contentDiv = document.getElementById("content");
            let html = "<ul>";
            response.data.forEach(item => {
                html += `<li>ID: ${item.id}, Email: ${item.email}, Role: ${item.role}, Age: ${item.age}</li>`;
            });

            html += "</ul>";
            contentDiv.innerHTML = html;
        }
    };

    xhttp.open("GET", "http://localhost/cloud_storage/index.php/admin/users/list", true);
    xhttp.send();
}

if (adminListBtn) {
    adminListBtn.addEventListener('click', adminList);
}

const adminUpdateId = document.getElementById('adminUpdateId')
const adminUpdateEmail = document.getElementById('adminUpdateEmail')
const adminUpdateRole = document.getElementById('adminUpdateRole')
const adminUpdateAge = document.getElementById('adminUpdateAge')
const adminUpdateBtn = document.getElementById('adminUpdateBtn')

function adminUpdate() {
    const updateId = adminUpdateId.value;
    const updateEmail = adminUpdateEmail.value;
    const updateRole = adminUpdateRole.value;
    const updateIdAge = adminUpdateAge.value;

    xhttp.open("PUT", "http://localhost/cloud_storage/index.php/admin/users/update", true);
    xhttp.setRequestHeader("Content-type", "application/json");
    xhttp.send(JSON.stringify({ id: updateId, email: updateEmail, role: updateRole, age: updateIdAge }));

    adminUpdateId.value = ''
    adminUpdateEmail.value = ''
    adminUpdateRole.value = ''
    adminUpdateAge.value = ''
}

if (adminUpdateBtn) {
    adminUpdateBtn.addEventListener('click', adminUpdate)
}

const adminGetIdInput = document.getElementById('adminGetIdInput')
const adminGetBtn = document.getElementById('adminGetId')

function adminGetId() {
    const getId = adminGetIdInput.value;

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            const response = JSON.parse(this.responseText);
            console.log(response.data[0].id)
            const contentDiv = document.getElementById("content");
            let html = "<ul>";
            response.data.forEach(user => {
                html += `<li>ID: ${user.id}, Email: ${user.email}, Role: ${user.role}, Age: ${user.age}</li>`;
            });
            html += "</ul>";
            contentDiv.innerHTML = html;
        }
    };

    xhttp.open("GET", `http://localhost/cloud_storage/index.php/admin/users/get/${getId}`, true);
    xhttp.send();

    adminGetIdInput.value = ''

}

if (adminGetBtn) {
    adminGetBtn.addEventListener('click', adminGetId)
}

const adminDeleteInput = document.getElementById('adminDeleteInput')
const adminDeleteBtn = document.getElementById('adminDeleteId')

function adminDelete() {

    const deleteId = adminDeleteInput.value;

    xhttp.open("DELETE", "http://localhost/cloud_storage/index.php/admin/users/delete", true);
    xhttp.setRequestHeader("Content-type", "application/json");
    xhttp.send(JSON.stringify({ id: deleteId, }));

    adminDeleteInput.value = ''

}

if (adminDeleteBtn) {
    adminDeleteBtn.addEventListener('click', adminDelete)
}

//=================================login

const loginEmail = document.getElementById('loginEmail')
const loginPass = document.getElementById('loginPass')
const loginAge = document.getElementById('loginAge')
const loginAddBtn = document.getElementById('loginAddBtn')

function addUser() {

    const addEmail = loginEmail.value;
    const addPass = loginPass.value;
    const addAge = loginAge.value;

    xhttp.open("POST", "http://localhost/cloud_storage/index.php/users/register", true);
    xhttp.setRequestHeader("Content-type", "application/json");
    xhttp.send(JSON.stringify({ email: addEmail, password: addPass, age: addAge }));

    loginEmail.value = ''
    loginPass.value = ''
    loginAge.value = ''

}

if (loginAddBtn) {
    loginAddBtn.addEventListener('click', addUser)
}

const enterEmail = document.getElementById('enterEmail')
const enterPass = document.getElementById('enterPass')
const loginEnter = document.getElementById('loginEnter')

function login() {

    const loginEmail = enterEmail.value;
    const loginPass = enterPass.value;

    xhttp.open("POST", "http://localhost/cloud_storage/index.php/users/login", true);
    xhttp.setRequestHeader("Content-type", "application/json");
    xhttp.send(JSON.stringify({ email: loginEmail, password: loginPass }));

    enterEmail.value = ''
    enterPass.value = ''

}

if (loginEnter) {
    loginEnter.addEventListener('click', login)
}

const loginRemove = document.getElementById('loginRemove')

function logout() {

    xhttp.open("POST", "http://localhost/cloud_storage/index.php/users/logout", true);
    xhttp.setRequestHeader("Content-type", "application/json");
    xhttp.send();

}

if (loginRemove) {
    loginRemove.addEventListener('click', logout)
}

const resetBtn = document.getElementById('resetBtn')
const resetEmail = document.getElementById('resetEmail')

function reset() {

    const emailReset = resetEmail.value;

    xhttp.open("POST", "http://localhost/cloud_storage/index.php/users/reset_password", true);
    xhttp.setRequestHeader("Content-type", "application/json");
    xhttp.send(JSON.stringify({ email: emailReset }));

    resetEmail.value = ''
}

if (resetBtn) {
    resetBtn.addEventListener('click', reset)
}

//================================files

function downloadFile(fileId) {
    const link = document.createElement('a');
    link.href = `http://localhost/cloud_storage/index.php/files/download/${fileId}`;
    link.click();
}

const fileListBtn = document.getElementById('fileList')

function fileList() {

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            const response = JSON.parse(this.responseText);
            const contentDiv = document.getElementById("content");
            let html = "<ul>";
            response.data.forEach(item => {
                html += `<li><a href="#" onclick="downloadFile('${item.id}')">File name: ${item.file_name}`;
            });
            html += "</ul>";
            contentDiv.innerHTML = html;
        }
    };

    xhttp.open("GET", "http://localhost/cloud_storage/index.php/files/list", true);
    xhttp.send();
}

if (fileListBtn) {
    fileListBtn.addEventListener('click', fileList)
}

const getFileInput = document.getElementById('getFileInput')
const getFileBTn = document.getElementById('getFileBtn')

function getFile() {
    const getFileId = getFileInput.value;

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            const response = JSON.parse(this.responseText);
            const contentDiv = document.getElementById("content");
            let html = "<ul>";
            response.data.forEach(item => {
                html += `<li>File name: ${item.file_name}`;
            });
            html += "</ul>";
            contentDiv.innerHTML = html;
        }
    };

    xhttp.open("GET", `http://localhost/cloud_storage/index.php/files/get/${getFileId}`, true);
    xhttp.send();

    getFileInput.value = ''

}

if (getFileBTn) {
    getFileBTn.addEventListener('click', getFile)
}

const addFileInput = document.getElementById('addFileInput')
const addFileBTn = document.getElementById('addFileBtn')

function addFile() {

    const fileName = addFileInput.value.split('\\').pop().split('/').pop();

    xhttp.open("POST", "http://localhost/cloud_storage/index.php/files/add", true);
    xhttp.setRequestHeader("Content-type", "application/json");
    xhttp.send(JSON.stringify({ file_name: fileName }));

    addFileInput.value = ''
}

if (addFileBTn) {
    addFileBTn.addEventListener('click', addFile)
}

const renameFileInput = document.getElementById('renameFileInput')
const renameFileId = document.getElementById('renameFileId')
const renameFileBtn = document.getElementById('renameFileBtn')

function fileRename() {

    const updateId = renameFileId.value;
    const updateName = renameFileInput.value;

    xhttp.open("PUT", "http://localhost/cloud_storage/index.php/files/rename", true);
    xhttp.setRequestHeader("Content-type", "application/json");
    xhttp.send(JSON.stringify({ id: updateId, file_name: updateName }));

    renameFileInput.value = ''
    renameFileId.value = ''
}

if (renameFileBtn) {
    renameFileBtn.addEventListener('click', fileRename)
}


const deleteFileId = document.getElementById('deleteFileId')
const deleteFileBtn = document.getElementById('deleteFileBtn')

function fileDelete() {

    const deleteId = deleteFileId.value;

    xhttp.open("DELETE", `http://localhost/cloud_storage/index.php/files/remove/${deleteId}`, true);
    xhttp.setRequestHeader("Content-type", "application/json");
    xhttp.send(JSON.stringify({ id: deleteId }));

    deleteFileId.value = ''
}

if (deleteFileBtn) {
    deleteFileBtn.addEventListener('click', fileDelete)
}

const changeFileId = document.getElementById('changeFileId')
const changeFileDerectId = document.getElementById('changeFileDerectId')
const changeFileBtn = document.getElementById('changeFileBtn')

function changeDerectFile() {

    const updateId = changeFileId.value;
    const updateDerectId = changeFileDerectId.value;

    xhttp.open("POST", "http://localhost/cloud_storage/index.php/files/move", true);
    xhttp.setRequestHeader("Content-type", "application/json");
    xhttp.send(JSON.stringify({ id: updateId, derect_id: updateDerectId }));

    changeFileId.value = ''
    changeFileDerectId.value = ''
}

if (changeFileBtn) {
    changeFileBtn.addEventListener('click', changeDerectFile)
}

const changeSubFileId = document.getElementById('changeSubFileId')
const changeSubFileDerectId = document.getElementById('changeSubFileDerectId')
const changeSubFileBtn = document.getElementById('changeSubFileBtn')

function changeSubDerectFile() {

    const updateId = changeSubFileId.value;
    const updateDerectId = changeSubFileDerectId.value;

    xhttp.open("POST", "http://localhost/cloud_storage/index.php/files/submove", true);
    xhttp.setRequestHeader("Content-type", "application/json");
    xhttp.send(JSON.stringify({ id: updateId, derect_id: updateDerectId }));

    changeSubFileId.value = ''
    changeSubFileDerectId.value = ''
}

if (changeSubFileBtn) {
    changeSubFileBtn.addEventListener('click', changeSubDerectFile)
}

//===================derect

const addDerectInput = document.getElementById('addDerectInput')
const addDerectBTn = document.getElementById('addDerectBtn')

function addDerect() {

    const derectName = addDerectInput.value;

    xhttp.open("POST", "http://localhost/cloud_storage/index.php/directories/add", true);
    xhttp.setRequestHeader("Content-type", "application/json");
    xhttp.send(JSON.stringify({ derect_name: derectName }));

    addDerectInput.value = ''
}

if (addDerectBTn) {
    addDerectBTn.addEventListener('click', addDerect)
}

const addSubDerectInput = document.getElementById('addSubDerectInput')
const addSubDerectId = document.getElementById('addSubDerectId')
const addSubDerectBTn = document.getElementById('addSubDerectBtn')

function addSubDerect() {

    const addSubId = addSubDerectId.value
    const derectName = addSubDerectInput.value.split('\\').pop().split('/').pop();

    xhttp.open("POST", "http://localhost/cloud_storage/index.php/directories/addsub", true);
    xhttp.setRequestHeader("Content-type", "application/json");
    xhttp.send(JSON.stringify({ subDerect_name: derectName, subParent_id: addSubId }));

    addSubDerectId.value = ''
    addSubDerectInput.value = ''
}

if (addSubDerectBTn) {
    addSubDerectBTn.addEventListener('click', addSubDerect)
}

const renameDerectInput = document.getElementById('renameDerectInput')
const renameDerectId = document.getElementById('renameDerectId')
const renameDerectBtn = document.getElementById('renameDerectBtn')

function derectRename() {

    const updateId = renameDerectId.value;
    const updateName = renameDerectInput.value;

    xhttp.open("PUT", "http://localhost/cloud_storage/index.php/directories/rename", true);
    xhttp.setRequestHeader("Content-type", "application/json");
    xhttp.send(JSON.stringify({ id: updateId, derect_name: updateName }));

    renameDerectId.value = ''
    renameDerectInput.value = ''
}

if (renameDerectBtn) {
    renameDerectBtn.addEventListener('click', derectRename)
}

const deleteDerectId = document.getElementById('deleteDerectId')
const deleteDerectBtn = document.getElementById('deleteDerectBtn')

function derectDelete() {

    const deleteId = deleteDerectId.value;

    xhttp.open("DELETE", `http://localhost/cloud_storage/index.php/directories/delete/${deleteId}`, true);
    xhttp.setRequestHeader("Content-type", "application/json");
    xhttp.send(JSON.stringify({ id: deleteId }));

    deleteDerectId.value = ''
}

if (deleteDerectBtn) {
    deleteDerectBtn.addEventListener('click', derectDelete)
}

const getDerectId = document.getElementById('getDerectId')
const getDerectBtn = document.getElementById('getDerectBtn')

function getDerectFile() {
    const getId = getDerectId.value;

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            const response = JSON.parse(this.responseText);
            console.log(response.data)
            const contentDiv = document.getElementById("content");
            let html = "<ul>";
            html += `<li>имя папки: ${response.data.derectoriesName}`;
            response.data.files.forEach((item) => {
                html += `<li>имя файлов в папке ${response.data.derectoriesName}: ${item.file_name}`;
            })
            response.data.sub_directories.forEach((item) => {
                html += `<li>имя подпапки: ${item.sub_name}`;
            })
            response.data.sub_directories_files.forEach((item) => {
                html += `<li>файлы в подпапках: ${item.file_name}`;
            })
            html += "</ul>";
            contentDiv.innerHTML = html;
        }
    };

    xhttp.open("GET", `http://localhost/cloud_storage/index.php/directories/get/${getId}`, true);
    xhttp.send();

    getDerectId.value = ''

}

if (getDerectBtn) {
    getDerectBtn.addEventListener('click', getDerectFile)
}

//==========file acces

const fileAccessIdUser = document.getElementById('fileAccessIdUser')
const fileAccessIdFile = document.getElementById('fileAccessIdFile')
const fileAccessBtn = document.getElementById('fileAccessBtn')

function fileAccess() {

    const fileId = fileAccessIdFile.value;
    const userId = fileAccessIdUser.value;

    xhttp.open("PUT", `http://localhost/cloud_storage/index.php/files/share/${fileId}/${userId}`, true);
    xhttp.setRequestHeader("Content-type", "application/json");
    xhttp.send(JSON.stringify({ file_id: fileId, user_id: userId }));

    fileAccessIdUser.value = ''
    fileAccessIdFile.value = ''
}

if (fileAccessBtn) {
    fileAccessBtn.addEventListener('click', fileAccess)
}

const fileRemoveAccessIdUser = document.getElementById('fileRemoveAccessIdUser')
const fileRemoveAccessIdFile = document.getElementById('fileRemoveAccessIdFile')
const fileRemoveAccessBtn = document.getElementById('fileRemoveAccessBtn')

function fileRemoveAccess() {

    const fileId = fileRemoveAccessIdFile.value;
    const userId = fileRemoveAccessIdUser.value;

    xhttp.open("DELETE", `http://localhost/cloud_storage/index.php/files/remove_share/${fileId}/${userId}`, true);
    xhttp.setRequestHeader("Content-type", "application/json");
    xhttp.send(JSON.stringify({ file_id: fileId, user_id: userId }));

    fileRemoveAccessIdUser.value = ''
    fileRemoveAccessIdFile.value = ''
}

if (fileRemoveAccessBtn) {
    fileRemoveAccessBtn.addEventListener('click', fileRemoveAccess)
}

const fileGetIdUser = document.getElementById('fileGetIdUser')
const fileGetBtn = document.getElementById('fileGetBtn')

function getFileUser() {
    const getFileUser = fileGetIdUser.value;

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            const response = JSON.parse(this.responseText);
            const contentDiv = document.getElementById("content");
            console.log(response)
            let html = "<ul>";
            response.data.forEach(item => {
                html += `<li>email: ${item.email}, age: ${item.age}`;
            });
            html += "</ul>";
            contentDiv.innerHTML = html;
        }
    };

    xhttp.open("GET", `http://localhost/cloud_storage/index.php/files/share/${getFileUser}`, true);
    xhttp.send();

    fileGetIdUser.value = ''

}

if (fileGetBtn) {
    fileGetBtn.addEventListener('click', getFileUser)
}