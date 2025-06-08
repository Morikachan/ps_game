const submitBtn = document.querySelector(".regist-button");
const requiredElement = document.querySelectorAll("[required]");

const email = document.getElementById("email");
const password = document.getElementById("password");
const passwordCheck = document.getElementById("passwordCheck");
const username = document.getElementById("username");

const registrationInfo = {
  email: {
    element: email,
    elementError: document.getElementById("emailError"),
    elementErrorText: "メールを入力してください",
  },
  username: {
    element: username,
    elementError: document.getElementById("usernameError"),
    elementErrorText: "ユーザーネームを入力してください",
  },
};

const passwordInfo = {
  name: "password",
  element: password,
  elementError: document.getElementById("passwordError"),
  elementErrorText: "パスワードを入力してください",
};

const passwordCheckInfo = {
  name: "passwordCheck",
  element: passwordCheck,
  elementError: document.getElementById("passwordCheckError"),
  elementErrorText: "パスワード確認を入力してください",
};

const userFieldsInput = {
  username: null,
  email: null,
  password: null,
  passwordCheck: null,
};

submitBtn.addEventListener("click", (e) => {
  e.preventDefault();
});

for (let registrationKey in registrationInfo) {
  registrationInfo[registrationKey].element.addEventListener("change", () => {
    submitBtn.disabled = true;
    if (registrationInfo[registrationKey].element.value === "") {
      userFieldsInput[registrationKey] = null;
      registrationInfo[registrationKey].elementError.style.display =
        "inline-block";
      registrationInfo[registrationKey].elementError.textContent =
        registrationInfo[registrationKey].elementErrorText;
      registrationInfo[registrationKey].element.style.backgroundColor =
        "#FF8989";
    } else {
      userFieldsInput[registrationKey] =
        registrationInfo[registrationKey].element.value;
      registrationInfo[registrationKey].elementError.style.display = "none";
      registrationInfo[registrationKey].element.style.backgroundColor =
        "#FFFFFF";
      inputInfoToLocalStorage();
      checkFields();
    }
  });
}

password.addEventListener("change", () => {
  validationPassword(passwordInfo);
  validationPassword(passwordCheckInfo);
});

passwordCheck.addEventListener("change", () => {
  validationPassword(passwordCheckInfo);
});

const validationPassword = (elementInfo) => {
  const regex = /^[a-zA-Z0-9!#$%&?*@]+$/;
  const regexLength = /^.{8,36}$/;
  const regexBigLetter = /(?=.*[A-Z])/;
  const regexSmallLetter = /(?=.*[a-z])/;
  const regexNumber = /(?=.*[0-9])/;

  console.log(elementInfo.element.value);
  console.log(!regex.test(elementInfo.element.value));
  console.log(!regexLength.test(elementInfo.element.value));
  console.log(!regexBigLetter.test(elementInfo.element.value));

  if (elementInfo.element.value == "") {
    userFieldsInput[elementInfo.name] = null;
    elementInfo.elementError.style.display = "inline-block";
    elementInfo.elementError.textContent = elementInfo.elementErrorText;
    elementInfo.element.style.backgroundColor = "#FF8989";
    submitBtn.disabled = true;
  } else if (!regex.test(elementInfo.element.value)) {
    userFieldsInput[elementInfo.name] = null;
    elementInfo.elementError.style.display = "inline-block";
    elementInfo.elementError.textContent = "許可されていない文字種";
    elementInfo.element.style.backgroundColor = "#FF8989";
    submitBtn.disabled = true;
  } else if (
    !regexLength.test(elementInfo.element.value) &&
    elementInfo.name != "passwordCheck"
  ) {
    userFieldsInput[elementInfo.name] = null;
    elementInfo.elementError.style.display = "inline-block";
    elementInfo.elementError.textContent = "文字数は8文字から36文字まで";
    elementInfo.element.style.backgroundColor = "#FF8989";
    submitBtn.disabled = true;
  } else if (
    !regexBigLetter.test(elementInfo.element.value) &&
    elementInfo.name != "passwordCheck"
  ) {
    userFieldsInput[elementInfo.name] = null;
    elementInfo.elementError.style.display = "inline-block";
    elementInfo.elementError.textContent = "大文字を使用してください";
    elementInfo.element.style.backgroundColor = "#FF8989";
    submitBtn.disabled = true;
  } else if (
    !regexSmallLetter.test(elementInfo.element.value) &&
    elementInfo.name != "passwordCheck"
  ) {
    userFieldsInput[elementInfo.name] = null;
    elementInfo.elementError.style.display = "inline-block";
    elementInfo.elementError.textContent = "小文字を使用してください";
    elementInfo.element.style.backgroundColor = "#FF8989";
    submitBtn.disabled = true;
  } else if (
    !regexNumber.test(elementInfo.element.value) &&
    elementInfo.name != "passwordCheck"
  ) {
    userFieldsInput[elementInfo.name] = null;
    elementInfo.elementError.style.display = "inline-block";
    elementInfo.elementError.textContent = "数字を使用してください";
    elementInfo.element.style.backgroundColor = "#FF8989";
    submitBtn.disabled = true;
  } else if (elementInfo.element.value !== password.value) {
    userFieldsInput[elementInfo.name] = null;
    elementInfo.elementError.style.display = "inline-block";
    elementInfo.elementError.textContent =
      "パスワードとパスワード確認が違います";
    elementInfo.element.style.backgroundColor = "#FF8989";
    submitBtn.disabled = true;
  } else {
    elementInfo.elementError.style.display = "none";
    elementInfo.element.style.backgroundColor = "#FFFFFF";
    userFieldsInput[elementInfo.name] = elementInfo.element.value;
    inputInfoToLocalStorage();
  }
  checkFields();
};

const inputInfoToLocalStorage = () => {
  for (let field in userFieldsInput) {
    localStorage.setItem(field, userFieldsInput[field]);
  }
};

const checkLocalStorage = () => {
  for (let registrationKey in registrationInfo) {
    if (
      localStorage.getItem(registrationKey) !== null &&
      localStorage.getItem(registrationKey) !== "null"
    ) {
      registrationInfo[registrationKey].element.value =
        localStorage.getItem(registrationKey);
    }
    userFieldsInput[registrationKey] = localStorage.getItem(registrationKey);
  }
  password.value = localStorage.getItem("password");
  userFieldsInput.password = localStorage.getItem("password");
  passwordCheck.value = localStorage.getItem("passwordCheck");
  userFieldsInput.passwordCheck = localStorage.getItem("passwordCheck");
  validationPassword(passwordInfo);
  validationPassword(passwordCheckInfo);
  checkFields();
};

const checkFields = () => {
  const isAnyFieldEmpty = Object.values(userFieldsInput).some(
    (value) => value === null || value === "null" || value === ""
  );

  button.disabled = isAnyFieldEmpty;
};

window.addEventListener("load", () => {
  checkLocalStorage();
});
