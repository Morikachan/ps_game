const modalName = document.querySelector("#modalName");
const openName = document.querySelector("#changeUsernameOpen");
const changeName = document.querySelector("#changeNameConfirm");
const closeName = document.querySelector("#changeNameClose");
const usernameInput = document.querySelector("#homeUsername");

function openNameModal() {
  modalName.style.display = "block";
}

openName.addEventListener("click", openNameModal);

function updateUsername() {
  const userData = { newUsername: usernameInput.value };
  fetch("./changeUsername.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams(userData),
  })
    .then((response) => response.json())
    .then((responseData) => {
      if (responseData.status === true) {
        modalName.style.display = "none";
        setTimeout(() => {
          window.location.reload();
        }, 50);
      } else {
        alert("失敗発生");
      }
    });
}

changeName.addEventListener("click", updateUsername);

changeNameClose.onclick = function () {
  modalName.style.display = "none";
};
