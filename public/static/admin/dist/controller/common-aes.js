
var key_num = '1234567890654321';
var iv_num = '1234567890123456';

//前端js，使用crypto-js对数据进行AES加密
function encrypt(text) {
    var key = CryptoJS.enc.Latin1.parse(key_num); //为了避免补位，直接用16位的秘钥
    var iv = CryptoJS.enc.Latin1.parse(iv_num); //16位初始向量
    var encrypted = CryptoJS.AES.encrypt(text, key, {
        iv: iv,
        mode: CryptoJS.mode.CBC,
        padding: CryptoJS.pad.ZeroPadding
    });
    return encrypted.toString();
}

//解密
function decrypt(text) {
    var key = CryptoJS.enc.Latin1.parse(key_num); //为了避免补位，直接用16位的秘钥
    var iv = CryptoJS.enc.Latin1.parse(iv_num); //16位初始向量
    var decrypted = CryptoJS.AES.decrypt(text, key, {
        iv: iv,
        mode: CryptoJS.mode.CBC,
        padding: CryptoJS.pad.ZeroPadding
    });
    decrypted = CryptoJS.enc.Utf8.stringify(decrypted);
    return decrypted;
}