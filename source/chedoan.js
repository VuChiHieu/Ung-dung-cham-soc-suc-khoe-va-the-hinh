const sliderImages = [
    "img/chedoan/library/pic1.png",
    "img/chedoan/library/pic2.png",
    "img/chedoan/library/pic3.jpg",
    "img/chedoan/library/pic4.jpg"
];

let current = 0;
const slider = document.getElementById("sliderImg");

setInterval(() => {
    current = (current + 1) % sliderImages.length;
    slider.src = sliderImages[current];
}, 4000);

function typeText(elementId, text, speed = 50) {
  let i = 0;
  const element = document.getElementById(elementId);
  element.innerHTML = ""; 

  function type() {
    if (i < text.length) {
      element.innerHTML += text.charAt(i);
      i++;
      setTimeout(type, speed);
    }
  }

  type();
}

document.addEventListener("DOMContentLoaded", function () {
  const target = document.querySelector(".about");
  let hasTypedOnce = false;

  const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      target.classList.add("show");
      target.classList.remove("hidden");

      // Reset lại nội dung gõ
      const element = document.getElementById("aboutTyping");
      element.innerHTML = "";
      typeText("aboutTyping", "Dinh dưỡng là nền tảng của sức khỏe");
    }
  });
  }, {
    threshold: 0.2
  });

  if (target) {
    observer.observe(target);
  }
});

const mealData = {
  sang: {
    title: "Bữa sáng nên ăn gì?",
    desc: "Bữa sáng được mệnh danh là bữa ăn của nhà vua vì vai trò thiết yếu trong việc khởi động quá trình trao đổi chất và cung cấp năng lượng cho cả ngày. Sau một đêm dài, cơ thể cần được nạp lại năng lượng để chuẩn bị cho các hoạt động thể chất và tinh thần. Một bữa sáng cân bằng không chỉ giúp bạn tỉnh táo, tập trung mà còn cải thiện tâm trạng và hiệu suất làm việc. Để có một bữa sáng lý tưởng, hãy ưu tiên các thực phẩm giàu chất xơ, protein nạc, carbonhydrate phức hợp và chất béo lành mạnh. Chất xơ từ rau củ, trái cây, và ngũ cốc giúp tiêu hóa tốt và no lâu. Protein nạc như trứng, sữa, sữa chua giúp xây dựng cơ bắp và duy trì cảm giác no. Carbonhydrate phức hợp từ yến mạch, bánh mì nguyên cám cung cấp năng lượng bền vững. Cuối cùng, chất béo lành mạnh từ bơ, các loại hạt hỗ trợ hấp thụ vitamin.",
    images: [
      { src: "img/chedoan/sang/comtam.png", name: "Cơm tấm" },
      { src: "img/chedoan/sang/banhmi.png", name: "Bánh mì" },
      { src: "img/chedoan/sang/pho.png", name: "Phở bò" },
      { src: "img/chedoan/sang/banhcanh.png", name: "Bánh canh" },
      { src: "img/chedoan/sang/xoi.png", name: "Các món xôi" },
      { src: "img/chedoan/sang/bunbo.png", name: "Bún bò" }
    ]
  },
  trua: {
    title: "Bữa trưa nên ăn gì?",
    desc: "Bữa trưa không chỉ là một bữa ăn giữa ngày mà còn là điểm tiếp nhiên liệu quan trọng giúp cơ thể và trí óc hoạt động hiệu quả suốt buổi chiều. Sau một buổi sáng tiêu hao năng lượng, một bữa trưa đầy đủ chất dinh dưỡng – cân bằng carbohydrate, protein, chất béo lành mạnh, vitamin và khoáng chất – sẽ giúp nạp lại năng lượng đã mất. Điều này cải thiện sự tập trung, tỉnh táo và hiệu suất làm việc, tránh tình trạng uể oải, mệt mỏi vào buổi chiều. Hơn nữa, ăn trưa đều đặn và đủ chất còn giúp kiểm soát cơn đói, ngăn ngừa ăn vặt quá nhiều, từ đó duy trì cân nặng hợp lý và sức khỏe tổng thể. Đừng bỏ qua bữa trưa, hãy ưu tiên những thực phẩm lành mạnh để có một buổi chiều tràn đầy năng lượng và hiệu quả..",
    images: [
      { src: "img/chedoan/trua/pic1.png", name: "Thịt + rau củ + hoa quả" },
      { src: "img/chedoan/trua/pic2.png", name: "Canh chua + thịt + đậu phụ" },
      { src: "img/chedoan/trua/pic3.png", name: "Gà + bò + cá + trứng" },
      { src: "img/chedoan/trua/pic4.png", name: "Thịt + rau + khoai tây + hoa quả" }
    ]
  },
  toi: {
    title:"Bữa tối nên ăn gì?",
    desc: "Một bữa tối được thiết kế hợp lý sẽ bao gồm các loại protein dễ tiêu hóa như thịt trắng (gà, cá), trứng, đậu phụ, giúp cơ thể hấp thu tối ưu cho quá trình phục hồi mà không gây gánh nặng cho hệ tiêu hóa. Carbohydrate phức hợp từ gạo lứt, khoai lang hay ngũ cốc nguyên hạt sẽ giải phóng năng lượng từ từ, duy trì cảm giác no và ngăn ngừa tình trạng đói bụng giữa đêm. Rau xanh đóng vai trò thiết yếu, cung cấp chất xơ, vitamin và khoáng chất, hỗ trợ tiêu hóa và đào thải độc tố. Hạn chế chất béo bão hòa và các món ăn nhiều dầu mỡ vào buổi tối là điều cần thiết để tránh đầy bụng, khó tiêu và ảnh hưởng đến giấc ngủ.",
    images: [
      { src: "img/chedoan/toi/pic1.jpg", name: "Salad cá hồi" },
      { src: "img/chedoan/toi/pic2.jpg", name: "Beefsteak" },
      { src: "img/chedoan/toi/pic3.jpg", name: "Rau củ" },
      { src: "img/chedoan/toi/pic4.jpg", name: "Salad"}
    ]
  }
};

const mealDataThuacan = {
  sang: {
    title: "Bữa sáng giảm cân nên ăn gì?",
    desc: "Bữa sáng là yếu tố quan trọng để khởi động quá trình trao đổi chất. Với chế độ giảm cân, bạn nên ưu tiên các thực phẩm giàu protein, chất xơ và tinh bột phức hợp để cung cấp năng lượng bền vững và tạo cảm giác no lâu. Hạn chế đồ ăn nhiều đường, dầu mỡ và chất béo không lành mạnh để tránh tích trữ calo dư thừa.",
    images: [
      { src: "img/thuacan/sang/coffee.jpg", name: "1 cà phê đen, 1 lát bánh mì, 1/2 quả cam" },
      { src: "img/thuacan/sang/banana.jpg", name: "1 lát bánh mì nướng, 1 quả trứng luộc, 2 quả chuối" },
      { src: "img/thuacan/sang/bread.jpg", name: "1 bánh mì nướng, 1 trứng luộc" },
      { src: "img/thuacan/sang/egg.jpg", name: "2 quả trứng luộc, 1 quả chuối" },
      { src: "img/thuacan/sang/milk.jpg", name: "1 ly sữa, 1 quả trứng luộc" },
    ]
  },
  trua: {
    title: "Bữa trưa giảm cân nên ăn gì?",
    desc: "Bữa trưa đóng vai trò then chốt trong việc duy trì năng lượng và sự tập trung cho buổi chiều mà không gây tích tụ mỡ thừa. Hãy lựa chọn các món ăn giàu protein nạc (như ức gà, cá, trứng), kết hợp với rau xanh và tinh bột phức hợp (gạo lứt, khoai lang) để cung cấp đủ chất dinh dưỡng, kiểm soát cơn đói và hỗ trợ quá trình giảm cân hiệu quả.",
    images: [
      { src: "img/thuacan/trua/bread.jpg", name: "1 lát bánh mì nướng, 1 hộp cá ngừ" },
      { src: "img/thuacan/trua/coffee.jpg", name: "Cà phê đen, 5-7 miếng bánh mặn, 1 trứng luộc" },
      { src: "img/thuacan/trua/rice.png", name: "1 chén cơm kèm ức gà luộc và 2 ly nước" },
      { src: "img/thuacan/trua/rice2.png", name: "1 chén cơm gạo lức kèm rau củ" },
      { src: "img/thuacan/trua/ucga.jpg", name: "Ức gà luộc, 1 quả trứng gà" }
    ]
  },
  toi: {
    title: "Bữa tối giảm cân nên ăn gì?",
    desc: "Bữa tối cho người giảm cân nên nhẹ nhàng, dễ tiêu hóa để không gây gánh nặng cho hệ tiêu hóa và ảnh hưởng đến giấc ngủ. Ưu tiên rau xanh, protein nạc và chất béo lành mạnh. Tránh các món ăn nhiều dầu mỡ, tinh bột tinh chế và đường để tối ưu hóa quá trình đốt cháy mỡ thừa trong khi ngủ.",
    images: [
      { src: "img/thuacan/toi/meat.jpg", name: "100g thịt, 100g đậu cô ve, 2-3 quả chuối, 1 quả táo" },
      { src: "img/thuacan/toi/raucu.png", name: "1 dĩa rau củ luộc, 1 quả ổi, 2 ly nước" },
      { src: "img/thuacan/toi/salad.jpg", name: "Salad trộn, súp rau củ + 2 ly nước" },
      { src: "img/thuacan/toi/soup.jpg", name: "Súp rau củ"}
    ]
  }
};

const mealDataThieucan = {
  sang: {
    title: "Bữa sáng cho người thiếu cân: Khởi đầu đầy năng lượng",
    desc: "Bữa sáng là nền tảng quan trọng để nạp lại năng lượng sau một đêm dài và kích hoạt quá trình trao đổi chất. Đối với người thiếu cân, bữa sáng cần phải giàu năng lượng, dễ tiêu hóa và hấp thu để tối đa hóa lượng calo và dưỡng chất. Ưu tiên các món ăn cung cấp cả tinh bột, protein và chất béo lành mạnh để có một khởi đầu ngày mới tràn đầy sức sống và hỗ trợ tăng cân lành mạnh.",
    images: [
      { src: "img/thieucan/sang/banhcanh.png", name: "Bánh canh" },
      { src: "img/thieucan/sang/bunbo.png", name: "Bún bò" },
      { src: "img/thieucan/sang/comtam.png", name: "Cơm tấm" },
      { src: "img/thieucan/sang/ngucoc.jpg", name: "Ngũ cốc với sữa" }, 
      { src: "img/thieucan/sang/pho.png", name: "Phở" }
    ]
  },
  trua: { 
    title: "Bữa trưa cho người thiếu cân: Tiếp năng lượng hiệu quả",
    desc: "Bữa trưa là thời điểm vàng để bổ sung năng lượng và dưỡng chất cho cơ thể sau buổi sáng hoạt động. Với người thiếu cân, bữa trưa cần được chú trọng với khẩu phần ăn đầy đủ các nhóm chất: tinh bột từ cơm, chất đạm từ thịt/cá/trứng và chất béo lành mạnh. Việc kết hợp đa dạng các món ăn sẽ giúp kích thích vị giác, đảm bảo đủ calo và protein cần thiết để hỗ trợ tăng cân bền vững.",
    images: [
      { src: "img/thieucan/trua/cachien.png", name: "Cá chiên, cơm, rau củ luộc" },
      { src: "img/thieucan/trua/galuoc.png", name: "Gà luộc/hấp, cơm , rau củ xào/luộc" },
      { src: "img/thieucan/trua/meat.jpg", name: "Thịt áp chảo với măng tây" }, 
      { src: "img/thieucan/trua/rice.jpg", name: "Cơm, thịt kho, rau củ xào/súp" } 
    ]
  },
  toi: {
    title: "Bữa tối cho người thiếu cân: Bổ sung dưỡng chất trước khi ngủ",
    desc: "Bữa tối cho người thiếu cân cần đảm bảo cung cấp đủ dưỡng chất để phục hồi cơ thể và hỗ trợ quá trình xây dựng cơ bắp trong khi ngủ. Ưu tiên các món ăn giàu protein dễ tiêu hóa, kết hợp với rau xanh và một lượng vừa phải tinh bột phức hợp. Tránh ăn quá no hoặc các món quá khó tiêu để không ảnh hưởng đến giấc ngủ, đồng thời vẫn đảm bảo đủ calo để tăng cân hiệu quả.",
    images: [
      { src: "img/thieucan/toi/beefsteak.png", name: "Beefsteak" },
      { src: "img/thieucan/toi/canhsuonnon.png", name: "Canh sườn non, cơm" },
      { src: "img/thieucan/toi/salmon.jpg", name: "Cá hồi áp chảo, cơm" },
      { src: "img/thieucan/toi/soup.jpg", name: "Súp bí đỏ bổ dưỡng" } 
    ]
  }
};

function setupMealSection(sectionId, mealData) {
  let currentMeal = "sang";
  let currentImgIndex = 0;

  const section = document.getElementById(sectionId);
  const mealBtns = section.querySelectorAll(".meal-btn");
  const mealImage = section.querySelector("img");
  const dishName = section.querySelector("#dish-name");
  const mealTitle = section.querySelector("#meal-title");
  const mealDesc = section.querySelector("#meal-description");
  const prevBtn = section.querySelector("#prev-btn");
  const nextBtn = section.querySelector("#next-btn");

  function updateContent() {
    const meal = mealData[currentMeal];
    const dish = meal.images[currentImgIndex];

    mealImage.classList.add("fade-out");
    setTimeout(() => {
      mealImage.src = dish.src;
      dishName.textContent = dish.name;

      mealTitle.textContent = meal.title || `Gợi ý món ăn cho bữa ${currentMeal}`;
      mealDesc.textContent = meal.desc || "Hãy lựa chọn thực phẩm lành mạnh phù hợp với mục tiêu sức khỏe của bạn.";

      mealImage.classList.remove("fade-out");
    }, 300);
  }

  mealBtns.forEach(btn => {
    btn.addEventListener("click", () => {
      mealBtns.forEach(b => b.classList.remove("active"));
      btn.classList.add("active");

      currentMeal = btn.dataset.meal;
      currentImgIndex = 0;
      updateContent();
    });
  });

  prevBtn.addEventListener("click", () => {
    const max = mealData[currentMeal].images.length;
    currentImgIndex = (currentImgIndex - 1 + max) % max;
    updateContent();
  });

  nextBtn.addEventListener("click", () => {
    const max = mealData[currentMeal].images.length;
    currentImgIndex = (currentImgIndex + 1) % max;
    updateContent();
  });

  updateContent();
}

setupMealSection("daydudu", mealData);
setupMealSection("thua-can", mealDataThuacan);
setupMealSection("thieucan", mealDataThieucan)

document.addEventListener("DOMContentLoaded", function () {
  const menuSection = document.querySelector(".menu");

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        menuSection.classList.add("show");
        menuSection.classList.remove("hidden");
        observer.unobserve(menuSection); // Chỉ chạy 1 lần
      }
    });
  }, {
    threshold: 0.2
  });

  if (menuSection) {
    observer.observe(menuSection);
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const section = document.querySelector(".nutrients-section");
  const boxes = document.querySelectorAll(".nutrient-box");

  const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        section.classList.add("show");

        // Thêm delay cho từng box
        boxes.forEach((box, i) => {
          box.style.animationDelay = `${i * 0.2}s`;
        });

        observer.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.2
  });

  if (section) {
    observer.observe(section);
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const section = document.querySelector(".dangerous-food");

  const observer = new IntersectionObserver((entries, obs) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        section.classList.add("show");
        section.classList.remove("hidden");
        obs.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.2
  });

  if (section) observer.observe(section);
});

document.getElementById("bmi-form").addEventListener("submit", function(e) {
  e.preventDefault();

  const height = parseFloat(document.getElementById("height").value);
  const weight = parseFloat(document.getElementById("weight").value);
  const resultBox = document.getElementById("bmi-result");

  
  if (
    isNaN(height) || isNaN(weight) ||
    height <= 0 || height > 250 ||
    weight <= 0 || weight > 300
  ) {
    resultBox.style.color = "red";
    resultBox.innerHTML = "⚠️ Vui lòng nhập chiều cao và cân nặng hợp lệ.";
    return;
  }

  fetch("save_bmi.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded"
    },
    body: new URLSearchParams({
      height,
      weight
    })
  })
  .then(res => res.text())
  .then(data => {
    resultBox.style.color = "#1a92be";
    resultBox.innerHTML = data;
  })
  .catch(() => {
    resultBox.style.color = "red";
    resultBox.textContent = "❌ Không thể kết nối tới máy chủ.";
  });
});
