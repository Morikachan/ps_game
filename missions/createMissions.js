const missionsContainer = document.querySelector("#missions-container");

let activeMissionType = 1;

setCompleteLogin(1, 0);

async function allMissionInfo() {
  const response = await fetch("./getAllDailyMissionsInfo.php", {
    method: "POST",
  });
  const responseData = await response.json();

  if (responseData.status === true) {
    const missionsList = responseData.missionsList;
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

function returnButtonType(isComplete, isReceived, missionId, isDaily) {
  let buttonElement =
    isComplete == 0 || (isComplete == null && isReceived == null)
      ? `<button type="button" class="mission-button" data-missionId="${missionId}">未クリア</button>`
      : (isComplete == 1 && isReceived == null) || isReceived == 0
      ? `<button type="button" class="mission-button clear" data-missionId="${missionId}" data-isDaily="${isDaily}">受け取る</button>`
      : `<button type="button" class="mission-button received" data-missionId="${missionId} data-isDaily="${isDaily} disabled">
            <img src="../src/completed.png" alt='ミッションクリア'>
        </button>`;

  return buttonElement;
}

function updateMissions(missionsList) {
  let currentListElements = "";
  missionsList.forEach((mission) => {
    if (mission.is_group_mission != 1) {
      currentListElements += `<div class="mission-element">
            <div class="mission-element-reward">
              ${returnItemImg(mission.reward_item_id)}
              <p id='mission_reward_amount'>x${mission.reward_amount}</p>
            </div>
            <p id='mission_text'>${mission.mission_text}</p>
            ${returnButtonType(
              mission.is_complete,
              mission.is_received,
              mission.mission_id,
              0
            )}
        </div>`;
    } else {
      currentListElements += `<div class="mission-element">
            <div class="mission-element-reward">
              ${returnItemImg(mission.group_reward_id)}
              <p id='mission_reward_amount'>x${mission.group_reward_amount}</p>
            </div>
            <p id='mission_text'>${mission.mission_daily_text}</p>
            ${returnButtonType(
              mission.all_cleared,
              mission.daily_received,
              mission.m_mission_daily_rewards_id,
              1
            )}
        </div>`;
    }
  });

  missionsContainer.innerHTML = currentListElements;
  addReceivingButtonListener();
}

function addReceivingButtonListener() {
  const receiveButtons = document.querySelectorAll(".mission-button");
  receiveButtons.forEach((button) => {
    if (button.classList.contains("clear"))
      button.addEventListener("click", (e) => {
        const missionId = e.currentTarget.dataset.missionid;
        const isDaily = e.currentTarget.dataset.isdaily;
        receiveReward(missionId, isDaily);
      });
  });
}

async function setCompleteLogin(missionId, isDaily) {
  const params = {
    mission_id: Number(missionId),
    is_daily: Number(isDaily),
    mission_num: 1,
  };
  const response = await fetch("./updateDailyMissionHistory.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams(params),
  });
  const responseData = await response.json();

  if (responseData.status === true) {
    setTimeout(() => {
      window.location.reload();
    }, 50);
  }
}

async function receiveReward(missionId, isDaily) {
  const params = {
    mission_id: Number(missionId),
    is_daily: Number(isDaily),
    is_receiving: 1,
    mission_num: 1,
  };
  const response = await fetch("./updateDailyMissionHistory.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams(params),
  });
  const responseData = await response.json();

  if (responseData.status === true) {
    clearButton = document.querySelector(`[data-missionid="${missionId}"]`);
    clearButton.classList.toggle("clear");
    clearButton.classList.toggle("received");
    clearButton.innerHTML = `<img src="../src/completed.png" alt='ミッションクリア'>`;
    setTimeout(() => {
      window.location.reload();
    }, 50);
  } else {
    console.log("エラーが発生しました。もう一度やり直してください。");
  }
}

allMissionInfo();
