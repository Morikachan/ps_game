const missionsContainer = document.querySelector("#missions-container");

let activeMissionType = 1;

async function allMissionInfo() {
  const response = await fetch("./getAllMissionsInfo.php", { method: "POST" });
  const responseData = await response.json();

  if (responseData.status === true) {
    const missionsList = responseData.missionsList;
    console.log(missionsList);
    updateMissions(missionsList);
  } else {
    alert("失敗発生");
  }
}

function returnItemImg(item) {
  let itemIconElement =
    item == 1
      ? "<img src='../src/gem_img.png' alt='ジェム画像'>"
      : item == 2
      ? " 有償<img src='../src/gem_img.png' alt='ジェム画像'>"
      : item == 3
      ? "<img src='../src/gold_coin_img.png' alt='コイン画像'>"
      : "<img src='../src/lvl_ticket.png' alt='チケット画像'>";

  return itemIconElement;
}

function returnButtonType(isComplete, isReceived, missionId) {
  let buttonElement =
    isComplete == 0 || (isComplete == null && isReceived == null)
      ? `<button type="button" class="mission-button" data-missionId="${missionId}">未クリア</button>`
      : (isComplete == 1 && isReceived == null) || isReceived == 0
      ? `<button type="button" class="mission-button clear" data-missionId="${missionId}">受け取る</button>`
      : `<button type="button" class="mission-button received" data-missionId="${missionId}">
            <img src="../src/check_icon.png" alt='ミッションクリア'>
        </button>`;

  return buttonElement;
}

function updateMissions(missionsList) {
  let currentListElements = "";
  missionsList.forEach((mission) => {
    console.log(mission);
    if (mission.mission_id != null) {
      currentListElements += `<div class="mission-element">
            ${returnItemImg(mission.reward_item_id)}
            <p id='mission_reward_amount'>${mission.reward_amount}個</p>
            <p id='mission_text'>${mission.mission_text}</p>
            ${returnButtonType(
              mission.is_complete,
              mission.is_received,
              mission.mission_id
            )}
        </div>`;
    } else {
      currentListElements += `<div class="mission-element">
            ${returnItemImg(mission.reward_item_id)}
            <p id='mission_reward_amount'>${mission.reward_amount}個</p>
            <p id='mission_text'>${mission.mission_daily_text}</p>
            ${returnButtonType(
              mission.is_complete,
              mission.is_received,
              mission.mission_id
            )}
        </div>`;
    }
  });

  missionsContainer.innerHTML = currentListElements;
  addReceivingButtonListener();
}

function addReceivingButtonListener() {
  const receiveButtons = document.querySelectorAll(".mission-receive-button");
  receiveButtons.forEach((button) => {
    if (button.classList.contains("clear"))
      button.addEventListener("click", (e) => {
        const missionId = e.currentTarget.dataset.missionId;
        receiveReward(missionId);
      });
  });
}

async function receiveReward(missionId) {
  const params = {
    mission_id: Number(missionId),
    is_received: true,
  };
  const response = await fetch("./updateMissionHistory.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams(params),
  });
  const responseData = await response.json();

  if (responseData.status === true) {
    clearButton = document.querySelector(`[data-foo="${missionId}"]`);
    clearButton.classList.toggle("clear");
    clearButton.classList.toggle("received");
    clearButton.innerHTML = `<img src="../src/check_icon.png" alt='ミッションクリア'>`;
  } else {
    console.log("エラーが発生しました。もう一度やり直してください。");
  }
}

allMissionInfo();
