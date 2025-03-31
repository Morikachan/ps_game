const gachaResMain = document.querySelector(".container-main-small");

const imgElement = document.getElementById("current-image");
const imageContainer = document.getElementById("image-container");
const resultContainer = document.querySelector(".gacha-result");
const resultOkBtn = document.querySelector("#gacha-result-ok");

const result = JSON.parse(window.localStorage.getItem("gachaResult"));

function cardShowcase() {
  fetch("../core/getCardList.php", {
    method: "POST",
  })
    .then((response) => response.json())
    .then((responseData) => {
      if (responseData.status === true) {
        const userCardList = responseData.cardList;
        gachaResMain.style.position = "static";
        showOneAndAllCard();
      } else {
        alert("失敗発生");
      }
    });
}

function showOneAndAllCard() {
  if (!Array.isArray(result) || result.length === 0) {
    resultOkBtn.addEventListener("click", () => {
      window.location.href = "./gachalist-page.php?gacha_id=" + result.gacha_id;
    });

    imgElement.src = `../src/cards/card_illust/card_${result.card_id}.jpg`;

    imgElement.addEventListener("click", () => {
      gachaResMain.style.position = "relative";
      imageContainer.style.display = "none";
      resultContainer.style.display = "grid";
      const img = document.createElement("img");
      img.src = `../src/cards/card_icons/card_icon_${result.card_id}.png`;
      resultContainer.appendChild(img);
    });
  } else {
    resultOkBtn.addEventListener("click", () => {
      window.location.href =
        "./gachalist-page.php?gacha_id=" + result[0].gacha_id;
    });

    let index = 0;
    imgElement.src = `../src/cards/card_illust/card_${result[index].card_id}.jpg`;

    imgElement.addEventListener("click", () => {
      if (index < result.length - 1) {
        index++;
        imgElement.src = `../src/cards/card_illust/card_${result[index].card_id}.jpg`;
      } else {
        gachaResMain.style.position = "relative";
        imageContainer.style.display = "none";
        resultContainer.style.display = "grid";

        result.forEach((card) => {
          const img = document.createElement("img");
          img.src = `../src/cards/card_icons/card_icon_${card.card_id}.png`;
          resultContainer.appendChild(img);
        });
      }
    });
  }
}

cardShowcase();
