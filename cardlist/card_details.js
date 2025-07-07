let detailsURL = new URLSearchParams(document.location.search);
let params = detailsURL.get("id");
let cardId = parseInt(params);

const cardInfoPicContainer = document.querySelector("#cardinfo-pic-container");
const cardInfoDetailsContainerUl = document.querySelector(
  "#cardinfo-details-container ul"
);
const cardImg = cardInfoPicContainer.querySelector("img");
cardImg.src = `../src/cards/card_illust/card_${cardId}.jpg`;

function createCardList() {
  fetch("../core/getCardList.php", {
    method: "POST",
  })
    .then((response) => response.json())
    .then((responseData) => {
      if (responseData.status === true) {
        const cardList = responseData.cardList;
        const currentCardData = cardList.find(
          (card) => card.card_id === cardId
        );
        cardInfoDetailsContainerUl.innerHTML = `
        <li id="card-info-icons">
        <img src="../src/rarity_${
          currentCardData.rarity_id
        }.png" alt="カードレアリティアイコン" id="card-rarity-icon">
          <img src="../src/type_${
            currentCardData.card_type_id
          }.PNG" alt="カードタイプアイコン" id="card-type-icon">
          <img src="../src/card_skill_group_${
            currentCardData.card_skill_group
          }.png" alt="カードスキールグループアイコン" id="card-skill-group-icon">
          <img src="../src/card_skill_target_${
            currentCardData.card_skill_target
          }.png" alt="カードスキールグループアイコン" id="card-skill-group-icon">
        </li>
        <li>カード名：${currentCardData.card_name}</li>
        <li>カードHP：${currentCardData.card_hp}</li>
        <li>カードタイプ：${currentCardData.card_type_name}</li>
        <li>カードスキールグループ：${currentCardData.card_skill_groupname}</li>
        <li>スキールに必須チャージ数：${currentCardData.card_charge}</li>
        <li>スキール：${currentCardData.card_skill_text}</li>
        <li>加入の日付：${currentCardData.add_date.split(" ")[0]}</li>
        `;
      } else {
        alert("失敗発生");
      }
    });
}

createCardList();
