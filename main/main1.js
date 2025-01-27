document.addEventListener('click', function (event) {
    if (event.target.tagName === 'A') {
        document.querySelectorAll('.navbar-nav .nav-item a').forEach(link => {
            link.style.border = 'none';
            link.style.color = '#fff';
        });

        if (window.innerWidth > 900) {
            event.target.style.borderBottom = '1px solid #000';
        } else {
            event.target.style.borderBottom = 'none';
        }
        event.target.style.color = '#000';
        updateActiveLink();
    }
});

const navLinks = document.querySelectorAll('.navbar-nav .nav-item a');
const sections = document.querySelectorAll('section');

function updateActiveLink() {
    let currentSectionId = '';

    sections.forEach(section => {
        const rect = section.getBoundingClientRect();
        if (rect.top <= window.innerHeight / 2 && rect.bottom >= window.innerHeight / 2) {
            currentSectionId = section.id;
        }
    });

    navLinks.forEach(link => {
        if (link.getAttribute('href') === `#${currentSectionId}`) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
}

const submit = document.querySelector(".sub");
const discard = document.querySelector(".discard");
const ok = document.querySelector(".ok");
const hide = document.getElementById("hide_page");
const form = document.querySelector("form");

let isSubmitConfirmed = false;
submit.addEventListener('click', (event) => {
    event.preventDefault();
    hide.style.display = "block";
    ok.addEventListener('click', () => {
        hide.style.display = "none";
        isSubmitConfirmed = true;
        if (isSubmitConfirmed) {
            form.submit();
            alert("Send Successfuly");
        }
    });
    discard.addEventListener('click', () => {
        hide.style.display = "none";
        isSubmitConfirmed = false;
    });
});

window.addEventListener('scroll', updateActiveLink);
updateActiveLink();
