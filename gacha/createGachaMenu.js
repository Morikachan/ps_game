const gachaSidebarContainer = document.querySelector(
  "#container-gacha-sidebar"
);
const gachaContainer = document.querySelector("#container-gacha");
// ガチャボタン
const onePullBtn = document.querySelector("#pull-one");
const tenPullBtn = document.querySelector("#pull-ten");
// ガチャボタンのジェム数
const onePullGemAmount = document.querySelector("#pull-amount-one");
const tenPullGemAmount = document.querySelector("#pull-amount-ten");
// 10連　1枚確定 情報
const guarantyInfo = document.querySelector(".pull-guaranty-info");
// 10連　1枚確定 情報
const endDateText = document.querySelector("#gacha-end-date");

let activeGachaId;

const gachaParams = {
  isActive: true,
};

function createGachaMenu() {
  fetch("./getAllGachaInfo.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams(gachaParams),
  })
    .then((response) => response.json())
    .then((responseData) => {
      if (responseData.status === true) {
        const gachaList = responseData.gachaList;
        let detailsURL = new URLSearchParams(document.location.search);
        let params = detailsURL.get("gacha_id") || "";

        if (params != "") {
          let gachaId = parseInt(params);
          activeGachaId = gachaId;
        } else {
          activeGachaId = gachaList[0].gacha_id;
        }
        gachaContainer.style.backgroundImage = `url('../src/banners/gacha_banner_${activeGachaId}.jpg')`;
        gachaSidebarContainer.innerHTML = createGachaSidebar(gachaList);
        addGachaSidebarListener(gachaList);
        updateGachaMenu(gachaList);
      } else {
        alert("失敗発生");
      }
    });
}

function createGachaSidebar(gachaList) {
  let sidebarElement = "";
  for (const gacha in gachaList) {
    sidebarElement +=
      gachaList[gacha].gacha_id == activeGachaId
        ? `<div class="gacha-sidebar-element active" style="background-image: url('../src/banners/gacha_banner_small_${gachaList[gacha].gacha_id}.jpg')" data-gachaId="${gachaList[gacha].gacha_id}"></div>`
        : `<div class="gacha-sidebar-element" style="background-image: url('../src/banners/gacha_banner_small_${gachaList[gacha].gacha_id}.jpg')" data-gachaId="${gachaList[gacha].gacha_id}"></div>`;
  }
  return sidebarElement;
}

function addGachaSidebarListener(gachaList) {
  const gachaSidebarElements = document.querySelectorAll(
    ".gacha-sidebar-element"
  );
  gachaSidebarElements.forEach((element) => {
    element.addEventListener("click", (e) => {
      //remove checked
      gachaSidebarElements.forEach((element) => {
        element.classList.remove("active");
      });
      //add checked to clicked banner
      e.target.classList.add("active");
      activeGachaId = Number(e.target.dataset.gachaid);
      updateGachaMenu(gachaList);
    });
  });
}

function updateGachaMenu(gachaList) {
  const activeGachaInfo = gachaList.find(
    (gachaValue) => gachaValue.gacha_id === activeGachaId
  );

  gachaContainer.style.backgroundImage = `url('../src/banners/gacha_banner_${activeGachaInfo.gacha_id}.jpg')`;
  const guarantyInfo = document.querySelector(".pull-guaranty-info");

  onePullGemAmount.textContent = activeGachaInfo.gem_amount1;
  tenPullGemAmount.textContent = activeGachaInfo.gem_amount10;

  onePullBtn.dataset.pullAmount = activeGachaInfo.gem_amount1;
  onePullBtn.setAttribute("data-gachaId", activeGachaId);
  tenPullBtn.dataset.pullAmount = activeGachaInfo.gem_amount10;
  tenPullBtn.setAttribute("data-gachaId", activeGachaId);

  if (activeGachaInfo.end_day != "0000-00-00 00:00:00") {
    endDateText.textContent = activeGachaInfo.end_day.slice(0, 16);
  } else {
    endDateText.textContent = "期限なし";
  }
  if (activeGachaInfo.type == 1) {
    guarantyInfo.textContent = "SSR1枚は確定";
    onePullBtn.classList.add("paid");
    tenPullBtn.classList.add("paid");
    onePullGemAmount.textContent = "【有償】" + activeGachaInfo.gem_amount1;
    tenPullGemAmount.textContent = "【有償】" + activeGachaInfo.gem_amount10;
  } else {
    guarantyInfo.textContent = "SR1枚は確定";
    onePullBtn.classList.remove("paid");
    tenPullBtn.classList.remove("paid");
  }

  if (activeGachaInfo.gem_amount1 == 0 || activeGachaInfo.gem_amount1 == -1) {
    onePullBtn.style.display = "none";
  } else {
    onePullBtn.style.display = "inline-block";
  }
  if (activeGachaInfo.gem_amount10 == 0 || activeGachaInfo.gem_amount10 == -1) {
    tenPullBtn.style.display = "none";
  } else {
    tenPullBtn.style.display = "inline-block";
  }
}

createGachaMenu();
