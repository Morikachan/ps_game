const selectionResultRaw = localStorage.getItem("selectionResult");
const selectionResult = selectionResultRaw
  ? JSON.parse(selectionResultRaw)
  : null;

let cardList = [];

let selectedCardIndex = null;
let selectedTargetIndex = null;
let selectedActionType = null; // "normal" or "skill"

async function getUserCardList() {
  try {
    const response = await fetch("../../core/getCardList.php", {
      method: "POST",
    });
    const responseData = await response.json();

    if (responseData.status === true && Array.isArray(responseData.cardList)) {
      const seenCardIds = new Set();
      return responseData.cardList.filter((card) => {
        if (!seenCardIds.has(card.card_id)) {
          seenCardIds.add(card.card_id);
          return true;
        }
        return false;
      });
    } else {
      alert("失敗発生");
    }
  } catch (error) {
    alert("通信エラーが発生しました");
  }
}

function isAttackOne(card) {
  return card.card_skill_group === 1 && card.card_skill_target === 0;
}
function isAttackAll(card) {
  return card.card_skill_group === 1 && card.card_skill_target === 1;
}
function isHealOne(card) {
  return card.card_skill_group === 2 && card.card_skill_target === 0;
}
function isHealAll(card) {
  return card.card_skill_group === 2 && card.card_skill_target === 1;
}

function createSkillCard(data) {
  const base = { ...data };

  const min = 70;
  const max = 120;

  function getRandomMultiplier() {
    const R = Math.floor(Math.random() * (max - min + 1));
    return R * 0.01 + min * 0.01;
  }

  function getTypeMultiplier(attackerType, targetType) {
    if (attackerType === 4 || targetType === 4) return 1;

    const matrix = {
      1: { 2: 1.25, 3: 0.75 }, // 水
      2: { 3: 1.25, 1: 0.75 }, // 火
      3: { 1: 1.25, 2: 0.75 }, // 木
    };

    return matrix[attackerType]?.[targetType] || 1;
  }

  function showEffect(game, target, multiplier) {
    const index = game.players.findIndex((p) => p.cards.includes(target));
    const cardIndex = game.players[index].cards.indexOf(target);
    const cardEl = document.querySelector(
      `[data-player="${index}"][data-index="${cardIndex}"]`
    );

    if (multiplier === 1.25 && cardEl) {
      const text = document.createElement("div");
      text.className = "effect-label";
      text.innerText = "CRITICAL";
      text.style.color = "orange";
      cardEl.appendChild(text);
      setTimeout(() => text.remove(), 800);
    }

    if (multiplier === 0.75 && cardEl) {
      const text = document.createElement("div");
      text.className = "effect-label";
      text.innerText = "WEAK";
      text.style.color = "skyblue";
      cardEl.appendChild(text);
      setTimeout(() => text.remove(), 800);
    }
  }

  if (isAttackOne(data)) {
    base.useSkill = (game, user, opponent, target) => {
      if (!target?.isAlive()) return;
      const multiplier = getRandomMultiplier();
      const typeMod = getTypeMultiplier(base.card_type_id, target.cardType);
      const damage = Math.floor(base.card_skill_amount * multiplier * typeMod);
      target.takeDamage(damage);
      showEffect(game, target, typeMod);
    };
  } else if (isAttackAll(data)) {
    base.useSkill = (game, user, opponent) => {
      opponent.cards.forEach((target) => {
        if (!target.isAlive()) return;
        const multiplier = getRandomMultiplier();
        const typeMod = getTypeMultiplier(base.card_type_id, target.cardType);
        const damage = Math.floor(
          base.card_skill_amount * multiplier * typeMod
        );
        target.takeDamage(damage);
        showEffect(game, target, typeMod);
      });
    };
  } else if (isHealOne(data)) {
    base.useSkill = (game, user, opponent, target) => {
      if (!target?.isAlive()) return;
      const multiplier = getRandomMultiplier();
      const heal = Math.floor(base.card_skill_amount * multiplier);
      target.heal(heal);
    };
  } else if (isHealAll(data)) {
    base.useSkill = (game, user, opponent) => {
      user.cards.forEach((target) => {
        if (!target.isAlive()) return;
        const multiplier = getRandomMultiplier();
        const heal = Math.floor(base.card_skill_amount * multiplier);
        target.heal(heal);
      });
    };
  } else {
    console.error("Unknown skill type/target:", data);
    throw new Error("Unknown skill type/target");
  }

  return base;
}

class Card {
  constructor(data) {
    this.id = data.card_id;
    this.name = data.name;
    this.hp = data.card_hp;
    this.maxHp = data.card_hp;
    this.charge = data.card_charge;
    this.cardType = data.card_type_id;
    this.card_skill_amount = data.card_skill_amount;
    this.card_skill_group = data.card_skill_group;
    this.card_skill_target = data.card_skill_target;
    this.useSkill = data.useSkill;
  }

  takeDamage(amount) {
    this.hp = Math.max(0, this.hp - amount);
    this.animateHpChange();
    this.showGlow("damage");

    const game = window.game;
    if (game) {
      const playerIdx = game.players.findIndex((p) => p.cards.includes(this));
      const cardIdx = game.players[playerIdx].cards.indexOf(this);
      game.showFloatingNumber(playerIdx, cardIdx, -amount);
    }
  }

  heal(amount) {
    this.hp = Math.min(this.maxHp, this.hp + amount);
    this.animateHpChange();
    this.showGlow("heal");

    const game = window.game;
    if (game) {
      const playerIdx = game.players.findIndex((p) => p.cards.includes(this));
      const cardIdx = game.players[playerIdx].cards.indexOf(this);
      game.showFloatingNumber(playerIdx, cardIdx, amount);
    }
  }

  animateHpChange() {
    setTimeout(() => {
      const allCards = document.querySelectorAll(".card");
      allCards.forEach((el) => {
        const idx = parseInt(el.dataset.index);
        const pid = parseInt(el.dataset.player);
        const card = window.game.players[pid].cards[idx];
        if (card === this) {
          const hpLine = el.querySelector(
            "br + text, br + div, br + span, br + strong"
          );
          el.querySelector("img").classList.add("hp-changed");
          setTimeout(
            () => el.querySelector("img").classList.remove("hp-changed"),
            300
          );
        }
      });
    }, 0);
  }

  showGlow(type) {
    setTimeout(() => {
      const allCards = document.querySelectorAll(".card");
      allCards.forEach((el) => {
        const idx = parseInt(el.dataset.index);
        const pid = parseInt(el.dataset.player);
        const card = window.game.players[pid].cards[idx];
        if (card === this) {
          if (type === "damage") {
            el.querySelector("img").classList.add("glow-red");
          } else if (type === "heal") {
            el.querySelector("img").classList.add("glow-green");
          }
          setTimeout(() => {
            el.querySelector("img").classList.remove("glow-red");
            el.querySelector("img").classList.remove("glow-green");
          }, 300);
        }
      });
    }, 0);
  }

  isAlive() {
    return this.hp > 0;
  }
}

class Player {
  constructor(name, cardIds, cardPool) {
    this.name = name;
    this.chargePoints = 6;
    this.cards = cardIds.map((id) => {
      const data = cardPool.find((c) => c.card_id === id);
      const skillCard = createSkillCard(data);
      return new Card(skillCard);
    });
  }

  get totalHp() {
    return this.cards.reduce((sum, c) => sum + c.hp, 0);
  }
}

class Game {
  constructor(cardPool) {
    if (!selectionResult) throw new Error("No selection result");
    this.players = [
      new Player("プレイヤー１", selectionResult[1].cards, cardPool),
      new Player("プレイヤー２", selectionResult[2].cards, cardPool),
    ];
    this.turn = 0;
    this.render();
  }

  currentPlayer() {
    return this.players[this.turn % 2];
  }

  opponentPlayer() {
    return this.players[(this.turn + 1) % 2];
  }

  forfeit(playerIndex) {
    const winner = this.players[1 - playerIndex];

    localStorage.setItem("battleWinner", winner.name);
    window.location.href = "../battle_result.php";
  }

  flashCard(targetCard) {
    const allCards = document.querySelectorAll(".card");
    allCards.forEach((el) => {
      const idx = parseInt(el.dataset.index);
      const pid = parseInt(el.dataset.player);
      const card = this.players[pid].cards[idx];
      if (card === targetCard) {
        el.querySelector("img").classList.add("flash-hit");
        setTimeout(
          () => el.querySelector("img").classList.remove("flash-hit"),
          300
        );
      }
    });
  }

  showFloatingNumber(playerIdx, cardIdx, value) {
    const container = document.querySelector(
      `[data-player="${playerIdx}"][data-index="${cardIdx}"]`
    );

    if (!container) return;

    const float = document.createElement("div");
    float.className = "floating-number";
    float.innerText = value > 0 ? `+${value}` : `${value}`;
    float.style.color = value > 0 ? "lightgreen" : "red";

    container.appendChild(float);

    setTimeout(() => float.remove(), 800);
  }

  playerAction(playerIndex) {
    if (this.turn % 2 !== playerIndex) return;

    const player = this.currentPlayer();
    const opponent = this.opponentPlayer();

    if (selectedCardIndex === null) {
      alert("まず自分のカードを選択してください");
      return;
    }

    if (selectedTargetIndex === null) {
      alert("ターゲットを選択してください");
      return;
    }

    const attacker = player.cards[selectedCardIndex];
    const target = opponent.cards[selectedTargetIndex];

    if (!target || !target.isAlive()) {
      alert("無効なターゲットです");
      return;
    }

    // Random multiplier
    const min = 70;
    const max = 120;
    const R = Math.floor(Math.random() * (max - min + 1));
    const multiplier = R * 0.01 + min * 0.01;

    // Type multiplier
    function getTypeMultiplier(attackerType, targetType) {
      if (attackerType === 4 || targetType === 4) return 1;

      const matrix = {
        1: { 2: 1.25, 3: 0.75 },
        2: { 3: 1.25, 1: 0.75 },
        3: { 1: 1.25, 2: 0.75 },
      };

      return matrix[attackerType]?.[targetType] || 1;
    }

    const typeMod = getTypeMultiplier(attacker.cardType, target.cardType);
    const damage = Math.floor(50 * multiplier * typeMod);

    target.takeDamage(damage);
    this.flashCard(target);

    // Show CRITICAL/WEAK label
    const index = this.players.findIndex((p) => p.cards.includes(target));
    const cardIndex = this.players[index].cards.indexOf(target);
    const cardEl = document.querySelector(
      `[data-player="${index}"][data-index="${cardIndex}"]`
    );

    if (typeMod === 1.25 && cardEl) {
      const label = document.createElement("div");
      label.className = "effect-label";
      label.innerText = "CRITICAL!";
      label.style.color = "orange";
      cardEl.appendChild(label);
      setTimeout(() => label.remove(), 800);
    }
    if (typeMod === 0.75 && cardEl) {
      const label = document.createElement("div");
      label.className = "effect-label";
      label.innerText = "WEAK";
      label.style.color = "skyblue";
      cardEl.appendChild(label);
      setTimeout(() => label.remove(), 800);
    }

    if (player.chargePoints < 6) {
      player.chargePoints += 1;
    }

    selectedCardIndex = null;
    selectedTargetIndex = null;
    this.nextTurn();
  }

  playerSkill(playerIndex) {
    if (this.turn % 2 !== playerIndex) return;

    const player = this.currentPlayer();
    const opponent = this.opponentPlayer();

    if (selectedCardIndex === null) {
      alert("まず自分のカードを選択してください");
      return;
    }

    const card = player.cards[selectedCardIndex];
    if (!card || card.charge <= 0 || player.chargePoints < card.charge) {
      alert("スキルが使えません");
      return;
    }

    // Single-target skills require a selected target
    if (
      (isAttackOne(card) || isHealOne(card)) &&
      selectedTargetIndex === null
    ) {
      alert("ターゲットを選択してください");
      return;
    }

    // Use skill
    if (card.card_skill_target === 0) {
      const targets =
        card.card_skill_group === 1 ? opponent.cards : player.cards;
      const target = targets[selectedTargetIndex];
      if (!target || !target.isAlive()) {
        alert("無効なターゲットです");
        return;
      }

      card.useSkill(this, player, opponent, target);
      this.flashCard(target); // Add visual feedback
    } else {
      card.useSkill(this, player, opponent); // AoE handled internally
    }

    player.chargePoints -= card.charge;
    selectedCardIndex = null;
    selectedTargetIndex = null;
    this.nextTurn();
  }

  prepareAction(actionType) {
    if (selectedCardIndex === null) {
      alert("まず自分のカードを選択してください");
      return;
    }

    selectedActionType = actionType;

    const card = this.currentPlayer().cards[selectedCardIndex];

    const isAoESkill =
      actionType === "skill" && (isAttackAll(card) || isHealAll(card));

    if (isAoESkill) {
      this.executeAction();
      return;
    }
    // else {
    //   document.getElementById("action-hint").innerText =
    //     "ターゲットを選んでください";
    // }

    this.render();
  }

  executeAction() {
    const player = this.currentPlayer();
    const opponent = this.opponentPlayer();

    const card = player.cards[selectedCardIndex];
    if (!card || !card.isAlive()) return;

    // Handle normal attack
    if (selectedActionType === "normal") {
      this.playerAction(this.turn % 2);
      return;
      // this.flashCard(target);
    }

    // Handle skill
    else if (selectedActionType === "skill") {
      if (card.charge > player.chargePoints) {
        alert("チャージが足りません");
        return;
      }

      const isAoE = isAttackAll(card) || isHealAll(card);

      if (!isAoE) {
        // Needs single target
        const targets =
          card.card_skill_group === 1 ? opponent.cards : player.cards;
        const target = targets[selectedTargetIndex];
        if (!target || !target.isAlive()) {
          alert("無効なターゲットです");
          return;
        }
        card.useSkill(this, player, opponent, target);
      } else {
        // AoE skill, no target
        card.useSkill(this, player, opponent);
      }

      player.chargePoints -= card.charge;
    }

    selectedCardIndex = null;
    selectedTargetIndex = null;
    selectedActionType = null;
    this.nextTurn();
  }
  nextTurn() {
    if (this.checkVictory()) return;
    this.turn++;

    selectedCardIndex = null;
    selectedTargetIndex = null;
    selectedActionType = null;

    setTimeout(() => this.render(), 300);
  }

  checkVictory() {
    for (let i = 0; i < 2; i++) {
      if (this.players[i].totalHp === 0) {
        const winner = this.players[1 - i];
        localStorage.setItem("battleWinner", winner.name);
        window.location.href = "battle_result.php";
        return true;
      }
    }
    return false;
  }

  render() {
    const currentPlayerIndex = this.turn % 2;

    document
      .getElementById("player1-buttons")
      .classList.toggle("hidden", currentPlayerIndex !== 0);
    document
      .getElementById("player2-buttons")
      .classList.toggle("hidden", currentPlayerIndex !== 1);
    document.getElementById("turn-info").innerText = `${
      this.currentPlayer().name
    } ターン`;

    document
      .getElementById("end-game-p1")
      .classList.toggle("hidden", currentPlayerIndex !== 0);
    document
      .getElementById("end-game-p2")
      .classList.toggle("hidden", currentPlayerIndex !== 1);

    const playerButtons = document.querySelectorAll(
      `#player${currentPlayerIndex + 1}-buttons button`
    );
    playerButtons.forEach((btn) => btn.classList.add("button-disabled"));

    // Enable buttons only if a card is selected
    if (selectedCardIndex !== null) {
      playerButtons.forEach((btn) => btn.classList.remove("button-disabled"));
    }

    ["game-cards-player1", "game-cards-player2"].forEach((id, idx) => {
      const container = document.getElementById(id);
      container.innerHTML = "";
      this.players[idx].cards.forEach((card, i) => {
        const div = document.createElement("div");
        div.className = "card";
        div.dataset.player = idx;
        div.dataset.index = i;

        const imagePath = `../../src/cards/card_game/game_icon_${card.id}.png`;

        let charge = "";
        for (let i = 0; i < card.charge; i++) {
          charge += `<div class="charge-full"></div>`;
        }
        let divCharge = `<div class="card-charges">${charge}</div>`;

        const skillIcon = `<img src="../../src/card_skill_group_${card.card_skill_group}.png" class="card-skill-info" alt="attacker">`;
        const targetIcon = `<img src="../../src/card_skill_target_${card.card_skill_target}.png" class="card-skill-info" alt="attacker">`;

        const tooltipText =
          cardList.find((c) => c.card_id === card.id)?.card_skill_text ||
          "スキル不明";

        div.innerHTML = `
            <img src="${imagePath}" alt="Card ${card.id}" class="card-image"><br>
            HP: ${card.hp}<br>
            ${divCharge}
            <div class="card-skill" title="${tooltipText}" style="font-size: 18px;">
              ${skillIcon} ${targetIcon}
            </div>
          `;

        if (!card.isAlive()) {
          div.querySelector("img").classList.add("dead");
        }

        // Selected card highlight
        if (idx === this.turn % 2 && i === selectedCardIndex) {
          div.querySelector("img").classList.add("selected-card");
        }

        // Selected target highlight
        if (idx !== this.turn % 2 && i === selectedTargetIndex) {
          div.querySelector("img").classList.add("selected-target");
        }

        const currentPlayerIndex = this.turn % 2;
        const player = this.currentPlayer();
        const skillBtn = document.querySelector(
          `#player${currentPlayerIndex + 1}-button-skill`
        );

        if (selectedCardIndex !== null) {
          const card = player.cards[selectedCardIndex];
          if (card.charge > player.chargePoints) {
            skillBtn.classList.add("button-disabled");
          } else {
            skillBtn.classList.remove("button-disabled");
          }
        } else {
          skillBtn.classList.add("button-disabled");
        }

        const selectedCard = this.currentPlayer().cards[selectedCardIndex];
        const isHeal =
          selectedActionType === "skill" && isHealOne(selectedCard);
        const isAttack =
          selectedActionType === "skill" && isAttackOne(selectedCard);
        const isNormalAttack = selectedActionType === "normal";

        const requiresTarget = isNormalAttack || isHeal || isAttack;

        const shouldHighlight =
          selectedCardIndex !== null &&
          selectedActionType !== null &&
          card.isAlive() &&
          requiresTarget &&
          ((isNormalAttack && idx !== this.turn % 2) ||
            (isAttack && idx !== this.turn % 2) ||
            (isHeal && idx === this.turn % 2));

        if (shouldHighlight) {
          div.querySelector("img").classList.add("selectable-target");
        }

        container.appendChild(div);
      });
    });

    for (let i = 0; i < 2; i++) {
      const chargeContainer = document.getElementById(`player${i + 1}-charge`);
      chargeContainer.innerHTML = "";

      const maxCharge = 6;
      const player = this.players[i];

      // Full charges
      for (let j = 0; j < player.chargePoints; j++) {
        const chargeDiv = document.createElement("div");
        chargeDiv.className = "charge-full";
        chargeContainer.appendChild(chargeDiv);
      }

      // Empty charges
      for (let y = 0; y < maxCharge - player.chargePoints; y++) {
        const emptyCharge = document.createElement("div");
        emptyCharge.className = "charge";
        chargeContainer.appendChild(emptyCharge);
      }
    }
    document
      .getElementById("player1-charge")
      .classList.toggle("hidden", currentPlayerIndex !== 0);
    document
      .getElementById("player2-charge")
      .classList.toggle("hidden", currentPlayerIndex !== 1);
  }
}

document.addEventListener("click", (e) => {
  const cardEl = e.target.closest(".card");
  if (!cardEl) return;

  const player = parseInt(cardEl.dataset.player);
  const index = parseInt(cardEl.dataset.index);
  const game = window.game;
  if (!game) return;

  const currentPlayerIndex = game.turn % 2;
  const selectedCard =
    selectedCardIndex !== null
      ? game.currentPlayer().cards[selectedCardIndex]
      : null;

  // If clicking your own card
  if (player === currentPlayerIndex) {
    const clickedCard = game.players[player].cards[index];
    if (!clickedCard.isAlive()) return;

    // Case 1: No card selected yet → select it
    if (selectedCardIndex === null) {
      selectedCardIndex = index;
    }
    // Case 2: Card already selected, no action yet → switch card
    else if (selectedActionType === null) {
      selectedCardIndex = index;
    }
    // Case 3: Skill requires targeting self (like heal)
    else if (selectedActionType === "skill" && isHealOne(selectedCard)) {
      selectedTargetIndex = index;
      game.executeAction();
      return;
    }

    // Always re-render after own-card logic
    game.render();
    return;
  }

  // Clicking opponent card
  if (selectedCardIndex === null) {
    alert("まず自分のカードを選んでください");
    return;
  }

  if (selectedActionType === null) {
    alert("通常攻撃またはスキルを選択してください");
    return;
  }

  // Handle attack target selection
  const isNormal = selectedActionType === "normal";
  const isAttack = selectedActionType === "skill" && isAttackOne(selectedCard);

  if (isNormal || isAttack) {
    selectedTargetIndex = index;
    game.executeAction();
  }
});

document.getElementById("end-game-p1").addEventListener("click", () => {
  window.game?.forfeit(0); // Player 1 gives up
});

document.getElementById("end-game-p2").addEventListener("click", () => {
  window.game?.forfeit(1); // Player 2 gives up
});

(async () => {
  const cardPool = await getUserCardList();
  if (cardPool) {
    cardList = cardPool;
    const gameInstance = new Game(cardPool);
    window.game = gameInstance;
  }
})();
