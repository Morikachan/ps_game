const gachaCardlistBtn = document.querySelector("#gacha-card-list");
const gachaHistoryBtn = document.querySelector("#gacha-history");

const modalMenuGacha = document.querySelector("#modalMenuGacha");
const modalMenuGachaCloseBtn = document.querySelector("#gachaMenuClose");
const modalMenuList = document.querySelector("#menuList");

function addCardlistButtonListener() {
  gachaCardlistBtn.addEventListener("click", async () => {
    const activeGacha = document.querySelector(".gacha-sidebar-element.active");
    const gachaHistoryId = Number(activeGacha.dataset.gachaid);
    const list = await getCardlist(gachaHistoryId);
    createModalCardList(list);
  });
}

function addGachaHistoryButtonListener() {
  gachaHistoryBtn.addEventListener("click", async () => {
    const gachaHistory = await getGachaHistory();
    createModalGachaHistory(gachaHistory);
  });
}

function createModalCardList(cardList) {
  let cardListGachaElements = `<li class="list-header">
          <div>カードアイコン</div>
          <div class="gacha-card-info">
              <p id="card-rarity">レアリティ</p>
              <p id="card-name">カード名</p>
              <p id="card-type">タイプ</p>
              <p id="card-skill">スキール</p>
          </div>
      </li>`;
  modalMenuGacha.style.display = "block";
  modalMenuGachaCloseBtn.addEventListener("click", () => {
    modalMenuGacha.style.display = "none";
  });
  cardList.forEach((card) => {
    cardListGachaElements += `<li>
        <div class="cardlist-icon" data-cardId="${card.card_id}" style="background-image: url('../src/cards/card_icons/card_icon_${card.card_id}.png')"></div>
        <div class="gacha-card-info">
            <p id="card-rarity"><img src="../src/rarity_${card.rarity_id}.png" alt="カードレアリティアイコン" id="card-rarity-icon"></p>
            <p id="card-name">${card.card_name}</p>
            <p id="card-type"><img src="../src/type_${card.card_type_id}.PNG" alt="カードタイプアイコン" id="card-type-icon"></p>
            <p id="card-skill"><img src="../src/card_skill_group_${card.card_skill_group}.png" alt="カードスキールグループアイコン" id="card-skill-group-icon"></p>
        </div>
    </li>`;
  });
  modalMenuList.innerHTML = cardListGachaElements;
}

function createModalGachaHistory(gachaHistory) {
  console.log(gachaHistory);
  let gachaHistoryElements = "";
  modalMenuGacha.style.display = "block";
  modalMenuGachaCloseBtn.addEventListener("click", () => {
    modalMenuGacha.style.display = "none";
  });
  gachaHistory.forEach((gacha) => {
    gachaHistoryElements += `<li class="gacha-history-element">
          <div class="gacha-banner" data-gachaId="${
            gacha.gacha_id
          }" style="background-image: url('../src/banners/gacha_banner_small_${
      gacha.gacha_id
    }.jpg')"></div>
          <div class="gacha-info">
              <p id="gacha-name">ガチャ名：${gacha.gacha_name}</p>
              <p id="gacha-pull-count">ガチャ回数：${gacha.pull} 回</p>
              <p id="gacha-date">引いた日付：${
                gacha.gacha_day.split(" ")[0]
              }</p>
          </div>
      </li>`;
  });
  modalMenuList.innerHTML = gachaHistoryElements;
}

async function getCardlist(gachaId) {
  const gachaData = {
    gacha_id: gachaId,
  };
  const response = await fetch("./getGachaCardlist.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams(gachaData),
  });
  const responseData = await response.json();
  if (responseData.status === true) {
    const gachaCardsList = responseData.gachaCardsList;
    return gachaCardsList;
  } else {
    throw new Error("失敗発生");
  }
}

async function getGachaHistory() {
  const response = await fetch("./getUserGachaHistory.php", {
    method: "POST",
  });
  const responseData = await response.json();
  if (responseData.status === true) {
    const userGachaHistory = responseData.userGachaHistory;
    return userGachaHistory;
  } else {
    throw new Error("失敗発生");
  }
}

addCardlistButtonListener();
addGachaHistoryButtonListener();
