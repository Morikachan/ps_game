const winner = localStorage.getItem("battleWinner");

const winnerName = document.querySelector("#winner-name");
winnerName.textContent = winner;

async function setCompleteBattle() {
  const params = {
    mission_id: 2,
    is_daily: 0,
    mission_num: 1,
  };
  const response = await fetch("../missions/updateDailyMissionHistory.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams(params),
  });
}

setCompleteBattle();
