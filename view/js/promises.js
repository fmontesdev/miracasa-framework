function ajaxPromise(sUrl, sType, sTData, sData = undefined) {
    // console.log('Hola ajaxPromise');
    // console.log(sUrl);
    // console.log(sType);
    // console.log(sTData);
    // console.log(sData);
    return new Promise((resolve, reject) => {
        $.ajax({
            url: sUrl,
            type: sType,
            dataType: sTData,
            data: sData
        }).done((data) => {
            // console.log(data);
            resolve(data);
        }).fail((jqXHR, textStatus, errorThrow) => {
            reject(errorThrow);
        }); 
    });
};