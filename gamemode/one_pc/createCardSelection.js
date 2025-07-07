let activePlayer = 1;

let playerSelections = {
  1: {
    name: "プレイヤー１",
    cards: [],
  },
  2: {
    name: "プレイヤー２",
    cards: [],
  },
};

const cardList = await getUserCardList();

async function getUserCardList() {
  const response = await fetch("../../core/getCardList.php", {
    method: "POST",
  });
  const responseData = await response.json();

  if (responseData.status === true) {
    const cardList = responseData.cardList;
    const uniqueCards = [];
    const seenCardIds = new Set();

    cardList.forEach((card) => {
      if (!seenCardIds.has(card.card_id)) {
        uniqueCards.push(card);
        seenCardIds.add(card.card_id);
      }
    });

    return uniqueCards;
  } else {
    alert("失敗発生");
  }
}

if (cardList) {
  renderCards(activePlayer);
  const timer1 = startTimer(1, () => {
    document.getElementById("player1").classList.add("ready");
    document.getElementById("player2").classList.remove("hidden");
    activePlayer = 2;
    renderCards(activePlayer);
    startTimer(activePlayer, nextPage);
  });

  document.querySelector("#confirm1").addEventListener("click", () => {
    if (playerSelections[1].cards.length < 3) autoSelect(1);
    clearInterval(timer1);
    document.getElementById("player1").classList.add("ready");
    document.querySelector("#player2").classList.remove("hidden");
    renderCards(2);
    startTimer(2, nextPage);
  });

  const confirm2 = document.querySelector("#confirm2");
  confirm2.addEventListener("click", () => {
    if (playerSelections[2].cards.length < 3) autoSelect(2);
    nextPage();
  });
}

function renderCards(playerNum) {
  const container = document.getElementById(`cards${playerNum}`);
  container.innerHTML = "";

  cardList.forEach((card) => {
    const cardDiv = document.createElement("div");
    cardDiv.classList.add("card");
    cardDiv.style.backgroundImage = `url('../../src/cards/card_icons/card_icon_${card.card_id}.png')`;
    cardDiv.dataset.cardId = card.card_id;

    let charge = "";
    for (let i = 0; i < card.card_charge; i++) {
      charge += `<div class="charge-full"></div>`;
    }
    let divCharge = document.createElement("div");
    divCharge.innerHTML = charge;
    divCharge.classList.add("card-charges");

    let cardInfo = document.createElement("div");
    cardInfo.classList.add("card-info");

    const skillIcon = `<img src="../../src/card_skill_group_${card.card_skill_group}.png" class="card-skill-info" alt="attacker">`;
    const targetIcon = `<img src="../../src/card_skill_target_${card.card_skill_target}.png" class="card-skill-info" alt="attacker">`;

    cardInfo.innerHTML = `<div class="card-skill">${skillIcon} ${targetIcon}</div>`;

    cardDiv.appendChild(divCharge);
    cardDiv.appendChild(cardInfo);
    cardDiv.addEventListener("click", () => {
      const selected = playerSelections[playerNum].cards;
      const alreadySelected = selected.includes(card.card_id);
      if (alreadySelected) {
        playerSelections[playerNum].cards = selected.filter(
          (id) => id !== card.card_id
        );
        let selectedIndex = selected.indexOf(card.card_id);
        if (selectedIndex > -1) {
          selected.splice(selectedIndex, 1);
        }
        cardDiv.classList.remove("selected");
      } else if (selected.length < 3) {
        playerSelections[playerNum].cards.push(card.card_id);
        cardDiv.classList.add("selected");
      }
      document.getElementById(`selected${playerNum}-count`).innerText =
        playerSelections[playerNum].cards.length;

      if (selected.length == 3) {
        document.querySelector(`#confirm${playerNum}`).disabled = false;
      } else {
        document.querySelector(`#confirm${playerNum}`).disabled = true;
      }
    });
    container.appendChild(cardDiv);
  });
}

function startTimer(playerNum, onConfirm) {
  let timeLeft = 60;
  const timerElem = document.getElementById(`timer${playerNum}`);
  const interval = setInterval(() => {
    timeLeft--;
    timerElem.innerText = timeLeft;
    if (timeLeft <= 0) {
      clearInterval(interval);
      autoSelect(playerNum);
      onConfirm();
    }
  }, 1000);
  return interval;
}

function autoSelect(playerNum) {
  const remaining = cardList
    .map((card) => card.card_id)
    .filter((id) => !playerSelections[playerNum].cards.includes(id));
  while (playerSelections[playerNum].cards.length < 3) {
    const rand = remaining.splice(
      Math.floor(Math.random() * remaining.length),
      1
    )[0];
    playerSelections[playerNum].cards.push(rand);
  }
}

function nextPage() {
  localStorage.setItem("selectionResult", JSON.stringify(playerSelections));

  fetch("../add_items.php", {
    method: "POST",
  })
    .then((response) => response.json())
    .then((responseData) => {
      if (responseData.status === true) {
        window.location.href = "battle_page.php";
      } else {
        alert("失敗発生");
      }
    });
}
