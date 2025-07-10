document.addEventListener("DOMContentLoaded", function () {
  const healthSection = document.querySelector(".health-intro");

  const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        healthSection.classList.add("show");

        // Cho ảnh và nội dung delay khác nhau
        const img = healthSection.querySelector(".img-box");
        const text = healthSection.querySelector(".intro-text");

        img.style.transitionDelay = "0.2s";
        text.style.transitionDelay = "0.4s";

        observer.unobserve(healthSection); // chỉ chạy 1 lần
      }
    });
  }, {
    threshold: 0.3
  });

  if (healthSection) {
    observer.observe(healthSection);
  }
});

document.addEventListener("DOMContentLoaded", () => {
  const section = document.querySelector(".health-factors");
  const cards = section.querySelectorAll(".factor-box");

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        cards.forEach((card, index) => {
          card.style.animation = `fadeUp 0.6s ease ${index * 0.2}s forwards`;
        });
        observer.unobserve(section);
      }
    });
  }, { threshold: 0.3 });

  observer.observe(section);
});

document.addEventListener("DOMContentLoaded", function () {
  const checklistSection = document.querySelector(".health-checklist");
  const checklistItems = document.querySelectorAll(".checklist li");
  let chartRendered = false;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        checklistItems.forEach((item, index) => {
          setTimeout(() => {
            item.classList.add("visible");
          }, index * 200); // delay theo từng dòng
        });

        if (!chartRendered) {
          const ctx = document.getElementById("healthChart").getContext("2d");
          new Chart(ctx, {
            type: "doughnut",
            data: {
              labels: ["Dinh dưỡng", "Vận động", "Giấc ngủ", "Tinh thần", "Nước"],
              datasets: [{
                data: [25, 30, 20, 15, 10],
                backgroundColor: ["#f39c12", "#3498db", "#9b59b6", "#e74c3c", "#1abc9c"],
                borderWidth: 2,
              }]
            },
            options: {
              cutout: "60%",
              plugins: {
                legend: {
                  display: true,
                  position: 'bottom',
                  labels: {
                    color: "#444",
                    padding: 20
                  }
                }
              }
            }
          });
          chartRendered = true;
        }

        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.3 });

  if (checklistSection) {
    observer.observe(checklistSection);
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const harmfulItems = [
    {
        img: "img/suckhoe/harmful/fastfood.jpg",
        title: "Đồ ăn nhanh",
        heading: "Đừng lạm dụng thức ăn nhanh",
        desc: "Thức ăn nhanh, với sự tiện lợi và hương vị hấp dẫn, đang trở thành một phần không thể thiếu trong cuộc sống hiện đại. Tuy nhiên, đằng sau vẻ ngoài hấp dẫn đó là hàm lượng cao các chất béo bão hòa, chất béo chuyển hóa (trans fat), muối và đường tinh luyện. Việc tiêu thụ thường xuyên và không kiểm soát loại thực phẩm này làm tăng đáng kể nguy cơ béo phì, dẫn đến các vấn đề sức khỏe nghiêm trọng như bệnh tim mạch, cao huyết áp, và tiểu đường loại 2. Không chỉ vậy, thiếu chất xơ và vitamin trong thức ăn nhanh còn ảnh hưởng xấu đến hệ tiêu hóa và làm suy yếu hệ miễn dịch của cơ thể."
    },
    {
        img: "img/suckhoe/harmful/salt.jpg",
        title: "Ăn quá nhiều muối",
        heading: "Hạn chế ăn quá nhiều muối",
        desc: "Muối là một gia vị thiết yếu, nhưng việc dư thừa muối trong chế độ ăn uống hàng ngày lại là một mối đe dọa thầm lặng đối với sức khỏe. Lượng natri cao từ muối là nguyên nhân hàng đầu làm tăng huyết áp, gây áp lực lớn lên hệ thống tim mạch và dẫn đến các bệnh nguy hiểm như đột quỵ và đau tim. Ngoài ra, việc tiêu thụ quá nhiều muối còn gây hại thận, làm thận phải làm việc quá sức để lọc bỏ natri dư thừa, lâu dần dẫn đến suy giảm chức năng thận. Tổ chức Y tế Thế giới (WHO) khuyến nghị giới hạn lượng muối dưới 5 gram mỗi ngày để bảo vệ sức khỏe tổng thể."
    },
    {
        img: "img/suckhoe/harmful/softdrink.jpg",
        title: "Nước ngọt có gas",
        heading: "Hạn chế nước ngọt",
        desc: "Nước ngọt có ga, với hương vị ngọt ngào và cảm giác sảng khoái tức thì, đã trở thành thức uống phổ biến của nhiều người. Tuy nhiên, chúng chứa một lượng đường khổng lồ dưới dạng siro ngô có hàm lượng fructose cao, góp phần trực tiếp vào tình trạng béo phì và tăng nguy cơ mắc bệnh tiểu đường loại 2. Đường trong nước ngọt còn là 'kẻ thù' của sức khỏe răng miệng, gây sâu răng nghiêm trọng. Hơn nữa, việc tiêu thụ thường xuyên còn ảnh hưởng xấu đến quá trình chuyển hóa năng lượng của cơ thể, gây áp lực lên gan và có thể dẫn đến bệnh gan nhiễm mỡ không do rượu."
    },
    {
        img: "img/suckhoe/harmful/night.jpg",
        title: "Thức khuya",
        heading: "Ngủ muộn hại sức khỏe",
        desc: "Trong xã hội hiện đại, thức khuya đã trở thành thói quen của không ít người. Tuy nhiên, việc thiếu ngủ kinh niên và rối loạn nhịp sinh học do thức khuya gây ra có những tác động tiêu cực sâu sắc đến sức khỏe. Nó không chỉ làm giảm sút trí nhớ và khả năng tập trung, ảnh hưởng đến hiệu suất làm việc và học tập, mà còn gây rối loạn nội tiết tố, làm tăng mức độ stress và lo âu. Về lâu dài, thức khuya còn làm tăng nguy cơ mắc các bệnh mạn tính như bệnh tim mạch, cao huyết áp, trầm cảm và suy giảm hệ miễn dịch, khiến cơ thể dễ bị tấn công bởi các tác nhân gây bệnh."
    },
    {
        img: "img/suckhoe/harmful/alcohol.jpg",
        title: "Uống nhiều rượu bia",
        heading: "Cẩn trọng với rượu bia",
        desc: "Rượu bia là một phần của nhiều sự kiện xã hội, nhưng việc lạm dụng hoặc uống quá mức có thể gây ra những tổn hại nghiêm trọng cho sức khỏe. Chất cồn trực tiếp ảnh hưởng đến gan, dẫn đến các bệnh như gan nhiễm mỡ, viêm gan do rượu, và xơ gan – những tình trạng có thể đe dọa tính mạng. Nó cũng tác động tiêu cực đến hệ thần kinh trung ương, gây suy giảm chức năng não bộ, rối loạn nhận thức và nguy cơ nghiện. Ngoài ra, rượu bia còn làm suy yếu hệ tiêu hóa, tăng nguy cơ viêm loét dạ dày và ảnh hưởng đến khả năng hấp thụ chất dinh dưỡng, đồng thời làm suy giảm hệ miễn dịch của cơ thể."
    },
    {
        img: "img/suckhoe/harmful/sedentary.jpg", 
        title: "Ít vận động",
        heading: "Vận động là chìa khóa cho sức khỏe",
        desc: "Trong thời đại công nghệ, việc dành quá nhiều thời gian ngồi một chỗ đã trở thành thói quen phổ biến, đặc biệt với công việc văn phòng hoặc giải trí tại nhà. Lối sống ít vận động là một trong những nguyên nhân hàng đầu dẫn đến béo phì, bệnh tim mạch, tiểu đường loại 2, và suy giảm khối lượng cơ xương. Việc thiếu hoạt động thể chất thường xuyên làm giảm quá trình trao đổi chất, ảnh hưởng đến tuần hoàn máu và làm tăng nguy cơ mắc các bệnh mãn tính nguy hiểm. Hãy cố gắng vận động ít nhất 30 phút mỗi ngày để duy trì sức khỏe và cải thiện chất lượng cuộc sống."
    },
    {
        img: "img/suckhoe/harmful/stress.jpg", 
        title: "Căng thẳng kéo dài",
        heading: "Quản lý stress hiệu quả",
        desc: "Trong cuộc sống đầy áp lực, căng thẳng (stress) kéo dài là một yếu tố gây hại sức khỏe thầm lặng nhưng cực kỳ nguy hiểm. Stress mãn tính không chỉ gây ra các vấn đề về sức khỏe tinh thần như lo âu, trầm cảm, mất ngủ, mà còn ảnh hưởng nghiêm trọng đến sức khỏe thể chất. Nó có thể làm suy yếu hệ miễn dịch, tăng nguy cơ mắc các bệnh tim mạch, rối loạn tiêu hóa, và làm trầm trọng thêm các tình trạng viêm nhiễm trong cơ thể. Học cách quản lý stress qua thiền định, tập thể dục, dành thời gian cho sở thích cá nhân hoặc tìm kiếm sự hỗ trợ chuyên nghiệp là rất quan trọng để duy trì một tinh thần khỏe mạnh."
    },
    {
        img: "img/suckhoe/harmful/smoking.jpg", 
        title: "Hút thuốc lá",
        heading: "Nói không với thuốc lá",
        desc: "Thuốc lá là một trong những tác nhân gây hại sức khỏe hàng đầu mà con người tự nguyện đưa vào cơ thể. Khói thuốc chứa hàng ngàn hóa chất độc hại, gây ra nhiều bệnh lý nghiêm trọng như ung thư phổi, ung thư vòm họng, bệnh phổi tắc nghẽn mãn tính (COPD), và các bệnh tim mạch. Hút thuốc không chỉ ảnh hưởng đến bản thân người hút mà còn gây hại cho những người xung quanh thông qua khói thuốc thụ động. Bỏ thuốc lá là một trong những quyết định quan trọng nhất để cải thiện và bảo vệ sức khỏe của chính bạn, gia đình và cộng đồng."
    },
    {
        img: "img/suckhoe/harmful/stimulants.jpg", 
        title: "Lạm dụng chất kích thích",
        heading: "Tránh xa chất kích thích",
        desc: "Các loại chất kích thích như ma túy, amphetamine, và các chất gây nghiện khác là mối đe dọa cực kỳ nghiêm trọng đối với sức khỏe thể chất và tinh thần. Việc lạm dụng chúng có thể dẫn đến nghiện ngập, gây tổn thương không thể hồi phục cho não bộ, hệ thần kinh trung ương, tim mạch và gan. Chúng còn làm suy giảm nghiêm trọng sức khỏe tinh thần, dẫn đến các bệnh lý như rối loạn tâm thần, ảo giác, lo âu và trầm cảm nặng. Hậu quả của việc sử dụng chất kích thích không chỉ ảnh hưởng đến cá nhân mà còn gây ra nhiều vấn đề xã hội phức tạp. Tránh xa hoàn toàn các loại chất kích thích là điều cần thiết để bảo vệ tính mạng và tương lai."
    }
];
  let currentIndex = 0;

  const img = document.getElementById("harmful-img");
  const title = document.getElementById("harmful-title");
  const heading = document.getElementById("harmful-heading");
  const desc = document.getElementById("harmful-desc");

  const prevBtn = document.getElementById("prev-harmful");
  const nextBtn = document.getElementById("next-harmful");

  function updateSlide() {
    const item = harmfulItems[currentIndex];
    img.src = item.img;
    title.textContent = item.title;
    heading.textContent = item.heading;
    desc.textContent = item.desc;
  }

  prevBtn.addEventListener("click", () => {
    currentIndex = (currentIndex - 1 + harmfulItems.length) % harmfulItems.length;
    updateSlide();
  });

  nextBtn.addEventListener("click", () => {
    currentIndex = (currentIndex + 1) % harmfulItems.length;
    updateSlide();
  });

  // Khởi tạo ban đầu
  updateSlide();
});

document.addEventListener("DOMContentLoaded", function () {
  const harmfulSection = document.querySelector(".harmful-foods");

  const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        harmfulSection.classList.add("show");
        observer.unobserve(entry.target); // chỉ hiện 1 lần
      }
    });
  }, {
    threshold: 0.3,
  });

  if (harmfulSection) {
    observer.observe(harmfulSection);
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const inspireSection = document.querySelector(".health-inspire");

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add("show");
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.3 });

  if (inspireSection) observer.observe(inspireSection);

  // 💡 QUAN TRỌNG: đặt phần xử lý form ở đây
  const form = document.getElementById("health-form");
  if (form) {
    form.addEventListener("submit", function(e) {
      e.preventDefault();

      const systolic = parseInt(document.getElementById("systolic").value);
      const diastolic = parseInt(document.getElementById("diastolic").value);
      const heartRate = parseInt(document.getElementById("heart_rate").value);
      const messageBox = document.getElementById("health-message");

      if (
        systolic <= 0 || systolic > 250 ||
        diastolic <= 0 || diastolic > 150 ||
        heartRate <= 0 || heartRate > 200
      ) {
        messageBox.style.color = "red";
        messageBox.textContent = "Vui lòng nhập các giá trị hợp lệ.";
        return;
      }

      fetch("save_health.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({
          systolic,
          diastolic,
          heart_rate: heartRate
        })
      })
      .then(res => res.text())
      .then(data => {
        messageBox.style.color = "#1a92be";
        messageBox.innerHTML = data;
      })
      .catch(() => {
        messageBox.style.color = "red";
        messageBox.textContent = "Lỗi khi kết nối máy chủ.";
      });
    });
  }
});
