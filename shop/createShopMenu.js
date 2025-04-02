const shopSidebarElements = document.querySelectorAll(".shop-bar-element");
const shopContainer = document.querySelector("#shop-container");

const shopModal = document.querySelector("#modalPurchase");
const modalPurchaseText = document.querySelector("#modalPurchaseText");
const modalCloseBtn = document.querySelector("#closeModalPurchase");
const modalShopTitle = document.querySelector("#modal-shop-title");

let activeShopId = 1;

async function allShopInfo() {
  const response = await fetch("./getShopInfo.php", { method: "POST" });
  const responseData = await response.json();

  if (responseData.status === true) {
    const shopList = responseData.shopList;
    addShopSidebarListener(shopList);
    updateShopMenu(shopList);
  } else {
    alert("失敗発生");
  }
}

function getGemsInfo(shopList) {
  const gemsList = shopList.filter(
    (value, index, self) =>
      self.findIndex(
        (v) => v.pack_id === value.pack_id && v.cost_m_item_id === 0
      ) === index
  );

  return gemsList;
}
function getPacksInfo(shopList) {
  const grouped = {};
  const packsList = [];

  shopList.forEach((item) => {
    if (!grouped[item.pack_group_id]) {
      grouped[item.pack_group_id] = [];
    }
    grouped[item.pack_group_id].push(item);
  });

  for (const key in grouped) {
    if (grouped[key].length > 1) {
      packsList.push(grouped[key]);
    }
  }

  shopList.forEach((item) => {
    if (item.cost_m_item_id === 3 && grouped[item.pack_group_id].length === 1) {
      packsList.push(item);
    }
  });

  return packsList;
}

function addShopSidebarListener(shopList) {
  shopSidebarElements.forEach((element) => {
    element.addEventListener("click", (e) => {
      shopSidebarElements.forEach((element) => {
        element.classList.remove("active");
      });
      e.currentTarget.classList.add("active");
      activeShopId = e.currentTarget.dataset.shopid;
      updateShopMenu(shopList);
    });
  });
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

function updateShopMenu(shopList) {
  console.log(activeShopId);
  let currentListElements = "";
  if (activeShopId == 1) {
    const gems = getGemsInfo(shopList);

    gems.forEach((element) => {
      //   console.log(element);
      currentListElements += `<div class="shop-element">
                <img src="../src/gem_img.png" alt="ジェム画像">
                <p id='item_amount'>${element.m_item_amount}個</p>
                <p id='pack_name'>${element.pack_name}</p>
                <button type="button" class="shop-buy-button" data-packId="${element.pack_group_id}">${element.cost_amount}円</button>
              </div>`;
    });
  } else {
    const packs = getPacksInfo(shopList);

    packs.forEach((element) => {
      if (!Array.isArray(element)) {
        currentListElements += `<div class="shop-element">
                      <img src="../src/pack_img_${
                        element.cost_m_item_id
                      }.png" alt="ジェム画像">
                      <p id='item_amount'>${
                        element.m_item_amount +
                        "" +
                        returnItemImg(element.m_item_id)
                      }</p>
                      <p id='pack_name'>${element.pack_name}</p>
                      <button type="button" class="shop-buy-button" data-packId="${
                        element.pack_group_id
                      }">${
          element.cost_amount + "" + returnItemImg(element.cost_m_item_id)
        }</button>
                    </div>`;
        console.log(element);
      } else {
        let packItems = "";
        element.forEach(
          (obj) =>
            (packItems += `<li>${
              obj.m_item_amount + "" + returnItemImg(obj.m_item_id)
            }</li>`)
        );

        currentListElements += `<div class="shop-element">
                      <img src="../src/pack_img_${
                        element[0].cost_m_item_id
                      }.png" alt="ジェム画像">
                      <ul id='item_amount'>${packItems}</ul>
                      <p id='pack_name'>${element[0].pack_name}</p>
                      <button type="button" class="shop-buy-button" data-packId="${
                        element[0].pack_group_id
                      }">${
          element[0].cost_amount + "" + returnItemImg(element[0].cost_m_item_id)
        }</button>
                    </div>`;
      }
    });
  }
  shopContainer.innerHTML = currentListElements;

  addBuyButtonListener();
}

function addBuyButtonListener() {
  const buyButtons = document.querySelectorAll(".shop-buy-button");
  buyButtons.forEach((button) => {
    button.addEventListener("click", (e) => {
      // e.currentTarget.classList.add("active");
      console.log(e.currentTarget);
      const chosePackId = e.currentTarget.dataset.packid;
      console.log(chosePackId);
      createPurchase(chosePackId);
    });
  });
}

async function createPurchase(packId) {
  const params = {
    pack_id: Number(packId),
  };
  const response = await fetch("./getShopResult.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams(params),
  });
  const responseData = await response.json();

  if (responseData.status === true) {
    const purchaseResult = responseData.purchaseResult;
    const purchasePackName = responseData.packName;
    shopModal.style.display = "block";
    modalShopTitle.textContent = "購入完了";
    modalPurchaseText.innerHTML = `【${purchasePackName}】が購入出来ました。<br> ありがとうございました。`;
    modalCloseBtn.addEventListener("click", () => {
      shopModal.style.display = "none";
      setTimeout(() => {
        window.location.reload();
      }, 50);
    });
  } else {
    shopModal.style.display = "block";
    modalShopTitle.textContent = "エラー発生";
    modalPurchaseText.textContent =
      "エラーが発生しました。もう一度やり直してください。";
    modalCloseBtn.addEventListener("click", () => {
      shopModal.style.display = "none";
    });
  }
}

allShopInfo();
