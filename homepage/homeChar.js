const modal = document.querySelector("#modalHomeChar");
const open = document.querySelector("#changeHomeCharOpen");
const changeChar = document.querySelector("#changeCardConfirm");
const close = document.querySelector("#changeCardClose");
const cardContainer = document.querySelector(".cardContainer");

let homeCardId = Number(
  document.querySelector(".game-container").dataset.cardid
);

function openCardList() {
  fetch("../core/getCardList.php", {
    method: "POST",
  })
    .then((response) => response.json())
    .then((responseData) => {
      if (responseData.status === true) {
        modal.style.display = "block";
        const cardList = responseData.cardList;
        const uniqueCards = [];
        const seenCardIds = new Set();

        cardList.forEach((card) => {
          if (!seenCardIds.has(card.card_id)) {
            uniqueCards.push(card);
            seenCardIds.add(card.card_id);
          }
        });

        // Creates cards cells
        function makeCardsGrid() {
          let cardCells = "";
          for (i = 0; i < uniqueCards.length; i++) {
            homeCardId === uniqueCards[i].card_id
              ? (cardCells += `<div class="card-icon checked" data-cardId="${uniqueCards[i].card_id}" style="background-image: url('../src/cards/card_icons/card_icon_${uniqueCards[i].card_id}.png')"></div>`)
              : (cardCells += `<div class="card-icon" data-cardId="${uniqueCards[i].card_id}" style="background-image: url('../src/cards/card_icons/card_icon_${uniqueCards[i].card_id}.png')"></div>`);
          }
          return cardCells;
        }

        // Creates a default grid
        cardContainer.innerHTML = makeCardsGrid();
        addCardListener();
      } else {
        alert("失敗発生");
      }
    });
}

function addCardListener() {
  const cards = document.querySelectorAll(".card-icon");
  cards.forEach((card) => {
    card.addEventListener("click", (e) => {
      //remove checked
      cards.forEach((card) => {
        card.classList.remove("checked");
      });
      //add checked to clicked icon
      e.target.classList.add("checked");
      homeCardId = Number(e.target.dataset.cardid);
    });
  });
}

function updateHomeChar(cardId) {
  const cardData = { cardId: cardId };
  fetch("./changeHomeChar.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams(cardData),
  })
    .then((response) => response.json())
    .then((responseData) => {
      if (responseData.status === true) {
        modal.style.display = "none";
        homeCardId = null;
        setTimeout(() => {
          window.location.reload();
        }, 50);
      } else {
        alert("失敗発生");
      }
    });
}

open.addEventListener("click", openCardList);

changeChar.addEventListener("click", () => updateHomeChar(homeCardId));

close.onclick = function () {
  modal.style.display = "none";
  homeCardId = Number(document.querySelector(".game-container").dataset.cardid);
};

window.onclick = function (event) {
  if (event.target == modal) {
    modal.style.display = "none";
    homeCardId = Number(
      document.querySelector(".game-container").dataset.cardid
    );
  }
};
