const winner = localStorage.getItem("battleWinner");

const winnerName = document.querySelector("#winner-name");
winnerName.textContent = winner;

const params = {
  mission_id: 2,
  is_daily: 0,
  mission_num: 1,
};

fetch("../missions/updateDailyMissionHistory.php", {
  method: "POST",
});
