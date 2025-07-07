const userRankingContainer = document.querySelector("#container-ranking");
async function insertUsers() {
  const response = await fetch("./getLvlRanking.php", { method: "POST" });
  const responseData = await response.json();

  if (responseData.status === true) {
    const userRanking = responseData.userList;
    let userRankingElements = "";

    let placement = 1;

    for (const user of userRanking) {
      const userLvl = await formatUserLvl(user.user_exp);

      userRankingElements += `<div class="user-element" data-userId="${
        user.user_id
      }">
          <p id="ranking_place">${placement}位</p>
          <img src="../src/cards/card_icons/card_icon_${
            user.home_card_id
          }.png" alt="ユーザーホームキャラ">
          <p id='user_lvl'>ユーザーレベル：${userLvl}</p>
          <p id="last_login">${formatLastLogin(user.last_login)}</p>
        </div>`;
      placement++;
    }

    userRankingContainer.innerHTML = userRankingElements;
  } else {
    alert("失敗発生");
  }
}

function formatLastLogin(datetime) {
  const now = new Date();
  const past = new Date(datetime);
  const diff = Math.floor(Math.abs(now - past) / 1000);
  let lastLoginFormattedStr = "";
  if (datetime == null) {
    lastLoginFormattedStr = "最終ログイン: なし";
  } else {
    switch (true) {
      case diff < 60:
        lastLoginFormattedStr = "最終ログイン: 1分以内";
        break;
      case diff < 3600:
        lastLoginFormattedStr = `最終ログイン: ${Math.floor(diff / 60)}分前`;
        break;
      case diff < 86400:
        lastLoginFormattedStr = `最終ログイン: ${Math.floor(
          diff / 3600
        )}時間前`;
        break;
      case diff < 2592000:
        lastLoginFormattedStr = `最終ログイン: ${Math.floor(diff / 86400)}日前`;
        break;
      case diff < 31536000:
        lastLoginFormattedStr = `最終ログイン: ${Math.floor(
          diff / 2592000
        )}か月前`;
        break;
      case diff >= 31536000:
        lastLoginFormattedStr = `最終ログイン: ${Math.floor(
          diff / 31536000
        )}年前`;
        break;
      default:
        lastLoginFormattedStr = "最終ログイン: なし";
        break;
    }
  }
  return lastLoginFormattedStr;
}

async function formatUserLvl(exp) {
  const response = await fetch("../core/getLvlRequirements.php", {
    method: "POST",
  });
  const responseData = await response.json();
  if (responseData.status === true) {
    const lvlTable = responseData.levelTable;
    let userLvl = 1;

    for (let level in lvlTable) {
      if (exp < lvlTable[level].exp_amount) {
        break;
      }
      userLvl = lvlTable[level].lvl;
    }

    return userLvl;
  } else {
    throw new Error("失敗発生");
  }
}

insertUsers();
