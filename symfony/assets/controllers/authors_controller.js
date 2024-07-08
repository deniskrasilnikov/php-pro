import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static values = {url: String}
    static targets = ["first", "prev", "next", "nav"]

    connect() {
        this.loadPage(this.urlValue, 1)
    }

    first(event) {
        event.preventDefault()
        this.loadPage(this.firstTarget.href, this.firstTarget.dataset.page)
    }

    prev(event) {
        event.preventDefault()
        this.loadPage(this.prevTarget.href, this.prevTarget.dataset.page)
    }

    next(event) {
        event.preventDefault()
        this.loadPage(this.nextTarget.href, this.nextTarget.dataset.page)
    }

    loadPage(url, page) {
        const self = this
        fetch(url) // виконання асинхронного веб запиту (AJAX-підхід) за допомогою вбудованої функції fetch()
            .then(response => response.text()) // повертаємо відповідь як є (у вигляді тексту)
            .then(function (html) {
                self.element.innerHTML = html // впроваджуємо текст відопвіді в існуючий елемент на сторінці
                self.navTarget.hidden = (page < 2) // ховаємо чи показуємо навігацію
            })
    }
}
