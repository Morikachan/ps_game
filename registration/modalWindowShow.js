const body = document.querySelector("body");
const modal = document.querySelector("#modal");
const button = document.querySelector("#createBtn");

button.addEventListener("click", insertConfirm);

const data = {
  email: localStorage.getItem("email"),
  password: localStorage.getItem("password"),
  username: localStorage.getItem("username"),
};

function insertConfirm() {
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
