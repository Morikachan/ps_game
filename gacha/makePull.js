const pullButtons = document.querySelectorAll(".pull-button");

const paidGems = Number(document.querySelector("#paid_gems").textContent);
const freeGems = Number(document.querySelector("#free_gems").textContent);
const modalErrorGacha = document.querySelector("#modalError");
const modalErrorGachaCloseBtn = document.querySelector("#gachaErrorClose");
const modalErrorText = document.querySelector("#errorText");

function addPullButtonListener() {
  pullButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const gachaId = Number(button.dataset.gachaid);
      const gachaType = button.classList.contains("paid");
      const gemAmount = Number(button.dataset.pullAmount);
      const pullNum = Number(button.dataset.pullcount);
      let checkResult = checkUserGems(gachaType, gemAmount);
      if (checkResult) {
        userGemRequired = gemRequirements(freeGems, gemAmount, gachaType);
        callPull(gachaId, gemAmount, userGemRequired, pullNum);
      } else {
        modalErrorText.innerHTML = gachaType
          ? "有償ジェムが足りません。<br>ショップへ行きますか？"
          : "ジェムが足りません。<br>ショップへ行きますか？";
        modalErrorGacha.style.display = "block";
        modalErrorGachaCloseBtn.addEventListener("click", () => {
          modalErrorGacha.style.display = "none";
        });
      }
    });
  });
}

function checkUserGems(gachaType, gemAmount) {
  let result = true;
  if (gachaType == 1) {
    paidGems - gemAmount < 0 ? (result = false) : (result = true);
  } else if (gachaType == 0) {
    paidGems + freeGems - gemAmount < 0 ? (result = false) : (result = true);
  }
  return result;
}

function gemRequirements(freeGems, gemAmount, gachaType) {
  const userGemRequirements = {
    freeGems: 0,
    paidGems: 0,
  };
  if (gachaType == 1) {
    userGemRequirements.paidGems = gemAmount;
  } else if (gachaType == 0) {
    if (freeGems >= gemAmount) {
      userGemRequirements.freeGems = gemAmount;
    } else {
      userGemRequirements.freeGems = freeGems;
      userGemRequirements.paidGems = gemAmount - freeGems;
    }
  }
  return userGemRequirements;
}

function callPull(gachaId, gachaGemAmount, userGemRequired, pullNum) {
  const params = {
    gacha_id: gachaId,
    gacha_gem_amount: gachaGemAmount,
    free_gem_amount: userGemRequired.freeGems,
    paid_gem_amount: userGemRequired.paidGems,
    pull_num: pullNum,
  };
  fetch("./generatePull.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams(params),
  })
    .then((response) => response.json())
    .then((responseData) => {
      if (responseData.status === true) {
        const gachaResult = responseData.gachaResult;
        if (gachaResult.length == 1) {
          let resultArray = [];
          resultArray.push(gachaResult);
          window.localStorage.setItem(
            "gachaResult",
            JSON.stringify(resultArray)
          );
        } else {
          window.localStorage.setItem(
            "gachaResult",
            JSON.stringify(gachaResult)
          );
        }
        window.location.href = "./gachaResult.php";
      } else {
        alert("失敗発生");
      }
    });
}

addPullButtonListener();
