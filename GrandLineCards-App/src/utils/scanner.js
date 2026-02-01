
import Tesseract from 'tesseract.js';

export const scanner = {
    /**
     * Recognize the card code from a given image source (Base64 or Blob).
     * It crops the bottom-right corner of the image where the code ID is usually located.
     * @param {string} imageSource - Base64 string of the image
     * @returns {Promise<string|null>} - The found code or null
     */
    async recognizeCardCode(imageSource) {
        try {
            // 1. Prepare image for cropping
            const croppedImage = await this.cropBottomRight(imageSource);

            // 2. Run Tesseract on the cropped area
            // Whitelist characters valid for One Piece Card Game codes (e.g., OP01-001, ST01-001, P-001)
            const result = await Tesseract.recognize(croppedImage, 'eng', {
                logger: m => console.log(m),
                tessedit_char_whitelist: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-'
            });

            const text = result.data.text.trim();
            console.log('OCR Raw Text:', text);

            // 3. Simple Regex to find code pattern like OP01-001 or equivalent
            const codePattern = /([A-Z]{1,4}[0-9]{1,2}-[0-9]{3,4})/i;
            const match = text.match(codePattern);

            if (match) {
                return match[0].toUpperCase();
            }

            return null;

        } catch (error) {
            console.error('OCR Error:', error);
            throw error;
        }
    },

    /**
     * Crops the bottom-right corner of the image.
     * Assuming the code is in the bottom 15% height and right 30% width.
     */
    cropBottomRight(base64Image) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            img.onload = () => {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');

                // Define crop area (Bottom Right)
                // Adjust these percentages based on actual card layout
                const cropWidth = img.width * 0.35;
                const cropHeight = img.height * 0.15;
                const startX = img.width - cropWidth;
                const startY = img.height - cropHeight;

                canvas.width = cropWidth;
                canvas.height = cropHeight;

                // Draw the cropped portion
                ctx.drawImage(img, startX, startY, cropWidth, cropHeight, 0, 0, cropWidth, cropHeight);

                resolve(canvas.toDataURL('image/png'));
            };
            img.onerror = reject;
            img.src = base64Image;
        });
    }
};
