const body = document.querySelector("body");
const modal = document.querySelector("#modalRegistration");
const button = document.querySelector("#createBtn");

button.addEventListener("click", insertConfirm);
function insertConfirm() {
  const data = {
    email: document.querySelector("#email").value,
    password: document.querySelector("#password").value,
    username: document.querySelector("#username").value,
  };

  fetch("createUser.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams(data),
  })
    .then((response) => response.json())
    .then((responseData) => {
      if (responseData.status === true) {
        body.style.overflow = "hidden";
        modal.style.display = "block";
        localStorage.clear();
      } else {
        alert("失敗発生");
      }
    });
}
