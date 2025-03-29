document.getElementById("desc").value =
  "Tento kus nábytku prináša dokonalú kombináciu štýlu, komfortu a funkčnosti. Navrhnutý s dôrazom na kvalitu a trvanlivosť, je ideálnou voľbou pre každý interiér. Jeho nadčasový dizajn a kvalitné materiály zaručujú, že sa hodí do rôznych miestností a štýlov zariadenia. Či už v obývacej izbe, spálni, kancelárii alebo jedálni, tento nábytok prinesie do vášho domova nielen pohodlie, ale aj estetický dojem. Jeho praktické využitie a moderný vzhľad ho robia ideálnym doplnkom každého priestoru.";

const productImagePaths = [
  { path: "assets/armchair/armchair-01.png", isMain: true },
  { path: "assets/armchair/armchair-01-1.png", isMain: false },
  { path: "assets/armchair/armchair-01-2.png", isMain: false },
  { path: "assets/armchair/armchair-01-3.png", isMain: false },
];

let detailImageLoaded = 1;

productImagePaths.forEach((productImage) => {
  let previewContainer;

  if (productImage.isMain) {
    previewContainer = document.getElementById("mainImagePreview");
  } else {
    previewContainer = document.getElementById(
      "detailImagePreview" + detailImageLoaded,
    );
    detailImageLoaded++;
  }

  const container = document.createElement("div");
  container.classList.add(
    "admin-image-container",
    "border",
    "border-secondary",
    "mt-3",
  );

  const deleteButton = document.createElement("button");
  deleteButton.innerHTML = "<i class='bi bi-trash'></i>";
  deleteButton.classList.add(
    "btn",
    "btn-sm",
    "btn-danger",
    "admin-delete-button",
  );

  deleteButton.onclick = () => {
    container.remove();
  };

  const img = document.createElement("img");
  img.classList.add("img-fluid");
  img.src = productImage.path;

  container.appendChild(deleteButton);
  container.appendChild(img);
  previewContainer.appendChild(container);
});
