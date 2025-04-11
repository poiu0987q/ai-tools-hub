document.getElementById("generateBtn").addEventListener("click", async () => {
  const prompt = document.getElementById("prompt").value;
  const loader = document.getElementById("loader");
  const outputImage = document.getElementById("outputImage");
  
  loader.style.display = "block";
  outputImage.src = "";

  const res = await fetch("backend/generate.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ prompt })
  });

  const data = await res.json();
  loader.style.display = "none";
  if (data.image_url) {
    outputImage.src = data.image_url;
  } else {
    outputImage.alt = "Failed to generate image.";
  }
});
