/* 
    2$imple (too Simple) JS Framework 
    -
    selectors, html content changer, css style changer, events, hide, show, toggle
*/

const $$ = function $$(selector, context = document) {
    const elements = Array.from(context.querySelectorAll(selector))

    return {
        elements,

        html(newHtml) {
            this.elements.forEach(element => {
                element.innerHTML = newHtml
            })
            return this
        },

        css(newCss) {
            this.elements.forEach(element => {
                Object.assign(element.style, newCss)
            })
            return this
        },

        on(event, handler, options) {
            this.elements.forEach(element => {
                element.addEventListener(event, handler, options)
            })
            return this
        },

        hide() {
            this.elements.forEach(element => {
                Object.assign(element.style, { display: 'none' })
            })
            return this
        },

        show() {
            this.elements.forEach(element => {
                Object.assign(element.style, { display: 'block' })
            })
            return this
        },

        toggle() {
            this.elements.forEach(element => {
                if (element.style.display === 'none') {
                    Object.assign(element.style, { display: 'block' })
                } else {
                    Object.assign(element.style, { display: 'none' })
                }
            })
            return this
        }

        // etc.
    }
}