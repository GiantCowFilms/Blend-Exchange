export default function (blend) {
    return `[<img src="https://blend-exchange.com/embedImage.png?bid=${ blend.id }" />](https://blend-exchange.com/b/${ blend.id }/)`;
}