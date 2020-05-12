
let posts = document.querySelectorAll(".posts");

posts.forEach(function(item) {
  let div = item.querySelectorAll("textarea");

  div.forEach(function(txt) {
    txt.style.height= (txt.scrollHeight-4) + "px";
  })
});
