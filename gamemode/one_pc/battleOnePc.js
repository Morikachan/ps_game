/*
      <div>
          <h2>プレイヤー１</h2>
          <div class="player-turn active" id="player1-turn"></div>
          <div class="player-hp" id="player1-hp"></div>
      </div>
      <div>
          <div class="game-attack-buttons active" id="player1-buttons">
              <button type="button" class="game-button" id="player1-button-normal"></button>
              <button type="button" class="game-button" id="player1-button-charge"></button>
          </div>
      </div>

  <div class="game-timer" id="game-timer"></div>

      <div>
          <h2>プレイヤー２</h2>
          <div class="player-turn" id="player2-turn"></div>
          <div class="player-hp" id="player2-hp"></div>
      </div>
      <div>
          <div class="game-attack-buttons" id="player2-buttons">
              <button type="button" class="game-button" id="player2-button-normal"></button>
              <button type="button" class="game-button" id="player2-button-charge"></button>
          </div>
      </div>
*/

const selectionResult = localStorage.getItem("selectionResult");

const gameCardList = await getUserCardList();

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

function createPlayerInfo(playerNum) {
  const playerCards = selectionResult[playerNum].cards;
  const playerTotalHP = document.querySelector(`player${playerNum}-hp`);
  let cardsTotalHp = 0;
  // <div class="player-turn active" id="player1-turn"></div>;

  playerCards.forEach((cardId) => {
    cardsTotalHp += playerCards.find(
      ({ card_id }) => card_id === cardId
    ).card_hp;
  });

  playerTotalHP.innerHTML = cardsTotalHp;
}

function gameRenderCards(playerNum) {
  const playerCardsSection = document.getElementById(
    `game-cards-player${playerNum}`
  );
  const playerCards = selectionResult[playerNum].cards;

  playerCards.forEach((cardId) => {
    const gameCardDiv = document.createElement("div");
    gameCardDiv.classList.add("game-card");
    gameCardDiv.style.backgroundImage = `url('../../src/cards/card_game/game_icon_${cardId}.png')`;
    gameCardDiv.dataset.cardId = cardId;

    gameCardDiv.addEventListener("click", () => {
      /* change
     <div class="game-attack-buttons active" id="player1-buttons">
          <button type="button" class="game-button" id="player1-button-normal"></button>
          <button type="button" class="game-button" id="player1-button-charge"></button>
      </div>
     */
    });
    playerCardsSection.appendChild(gameCardDiv);
  });
}

if (cardList) {
  gameRenderCards(1);
  gameRenderCards(2);
}
