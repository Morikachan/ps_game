const cardListContainer = document.querySelector("#cardlist-container");

function createCardList() {
  fetch("../core/getCardList.php", {
    method: "POST",
  })
    .then((response) => response.json())
    .then((responseData) => {
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
        // Creates cards cells
        function makeCardsGrid() {
          let cardCells = "";
          for (i = 0; i < uniqueCards.length; i++) {
            cardCells += `<div class="cardlist-icon" data-cardId="${uniqueCards[i].card_id}" style="background-image: url('../src/cards/card_icons/card_icon_${uniqueCards[i].card_id}.png')"></div>`;
          }
          return cardCells;
        }

        // Creates a default grid
        cardListContainer.innerHTML = makeCardsGrid();
        addCardListener();
      } else {
        alert("失敗発生");
      }
    });
}

createCardList();

function addCardListener() {
  const cards = document.querySelectorAll(".cardlist-icon");

  cards.forEach((card) => {
    card.addEventListener("click", (e) => {
      window.location.href = "./card_details.php?id=" + e.target.dataset.cardid;
    });
  });

  cards.forEach((card) => {
    card.addEventListener("pointerdown", (e) => {
      window.location.href = "./card_details.php?id=" + e.target.dataset.cardid;
    });
  });
}
