const addVideoFormDeleteLink = (item) => {
    const removeFormButton = document.createElement('button');
    removeFormButton.innerText = 'Supprimer cette vidéo';
    removeFormButton.className = "btn btn-light text-danger m-auto w-100";

    item.append(removeFormButton);

    removeFormButton.addEventListener('click', (e) => {
        e.preventDefault();
        // remove the li for the video form
        item.remove();
    });
}

const addFormToCollection = (e) => {
    const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

    const item = document.createElement('li');

    item.innerHTML = collectionHolder
        .dataset
        .prototype
        .replace(
            /__name__/g,
            collectionHolder.dataset.index
        );

    collectionHolder.appendChild(item);

    collectionHolder.dataset.index++;

    // add a delete link to the new form
    addVideoFormDeleteLink(item);
};

const collectionHelper = () => {
    document
        .querySelectorAll('.add_item_link')
        .forEach(btn => {
            btn.addEventListener("click", addFormToCollection)
        });
    document
        .querySelectorAll('ul.videos li')
        .forEach((video) => {
            addVideoFormDeleteLink(video)
        });
}

document.addEventListener('DOMContentLoaded', () => {
    console.log("salut");
    collectionHelper();
});
