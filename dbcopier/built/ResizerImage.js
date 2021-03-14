import { Ajax } from "./Ajax.js";
export class ResizerImage {
    constructor() {
        this.ajaxController = new Ajax();
    }
    saveImage(chooseFile, images) {
        chooseFile.addEventListener('change', (o) => {
            //If you don't need to resize the image, you can get the blob to upload from the 
            //FileList (e.g. doUpload(o.target.files[0]);
            let image = new Image();
            if (o.target.files.length > 0) {
                this.resizeImage(o.target.files[0], 1000, 1000).then(blob => {
                    //You can upload the resized image: doUpload(blob)
                    image.src = URL.createObjectURL(blob);
                    image.width = 200;
                    images.appendChild(image);
                    let reader = new FileReader();
                    reader.readAsDataURL(blob);
                    let base64data;
                    reader.onloadend = () => {
                        base64data = reader.result;
                        console.log(base64data);
                        this.sendImage(base64data);
                    };
                }, err => {
                    console.error("Photo error", err);
                });
            }
        });
    }
    resizeImage(file, maxWidth, maxHeight) {
        return new Promise((resolve, reject) => {
            let image = new Image();
            image.src = URL.createObjectURL(file);
            image.onload = () => {
                let width = image.width;
                let height = image.height;
                if (width <= maxWidth && height <= maxHeight) {
                    resolve(file);
                }
                let newWidth;
                let newHeight;
                if (width > height) {
                    newHeight = height * (maxWidth / width);
                    newWidth = maxWidth;
                }
                else {
                    newWidth = width * (maxHeight / height);
                    newHeight = maxHeight;
                }
                let canvas = document.createElement('canvas');
                canvas.width = newWidth;
                canvas.height = newHeight;
                let context = canvas.getContext('2d');
                context.drawImage(image, 0, 0, newWidth, newHeight);
                canvas.toBlob(resolve, file.type);
            };
            image.onerror = reject;
        });
    }
    sendImage(base64data) {
        let formData = new FormData();
        formData.append("image", base64data);
        this.ajaxController.sendAjaxRequestBlob("GET", "./dataDispatcher.php?saveImage", formData, (res) => {
            console.log("Blob da php: " + res);
        });
    }
    run() {
        //Prendo i dati statici dal DB 24
        this.saveImage(document.getElementById("choose"), document.getElementById("image"));
        //Ulteriori istruzioni eseguite nel suo callback
    }
}
