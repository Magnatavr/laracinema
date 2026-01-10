import './bootstrap';
import initSearch from './modules/search';
import modalTrailer from "./modules/trailer.js";
import likesToggle from "./modules/likes.js";
import editProfile from "./modules/edit-profile.js";
import filter from "./modules/filter.js";
import AjaxForHomePage from "./modules/ajaxForHomePage.js";

document.addEventListener('DOMContentLoaded', () => {
    AjaxForHomePage()
    initSearch();
    modalTrailer();
    likesToggle();
    editProfile();
    filter();
});

