function toggleSpecialProgramDisplay() {
    let switcher = document.getElementById("programSwitcher");
    switch(switcher.innerText) {
        case "toggle_off":
            switcher.innerText = "toggle_on";
            break;
        case "toggle_on":
            switcher.innerText = "toggle_off";
            break;
    }

    let displaySpecialPrograms = switcher.innerText === "toggle_off";

    let entries = document.querySelectorAll('[data-program-type="special_program"]');
    for (let entry of entries) {
        if (displaySpecialPrograms) {
            entry.classList.remove("is-hidden");
        } else {
            entry.classList.add("is-hidden");
        }
    }

    let movieLists = document.getElementsByClassName('movie-list is-filterable')
    for (let movieList of movieLists) {
        let visibleLinks = 0;

        for (let child of movieList.children[1].children) {
            if (child.tagName !== 'A') {
                continue;
            }

            if (!child.classList.contains('is-hidden')) {
                visibleLinks++;
            }
        }

        console.log(movieList, visibleLinks);

        if (visibleLinks === 0) {
            movieList.classList.add('is-hidden');
        } else {
            movieList.classList.remove('is-hidden');
        }
    }
}