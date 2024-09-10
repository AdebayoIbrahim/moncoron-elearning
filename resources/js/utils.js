// conver-blob-files-to-required-format-for-postrequet
export const convertBlobtofile = async (payload, filetype, cor) => {
    // check-for-fileispresent
    if (payload === "" || payload === null || payload === undefined) {
        return null;
    }
    const blobrequest = await fetch(payload);
    const blobfile = await blobrequest.blob();

    let filename;
    // check-file-type-for-saviing-format
    switch (filetype) {
        case "audio":
            filename = `audio_${cor}_${Date.now()}.mp3`;
            break;
        case "video":
            filename = `video_${cor}_${Date.now()}.mp4`;
            break;
        case "image":
            filename = `image_${cor}_${Date.now()}.png`;
            break;

        default:
            throw new Error(`Invalid File Type Format`);
    }

    const returnedFile = new File([blobfile], `${filename}`, {
        type: blobfile.type,
    });

    return returnedFile;
};

export function optionValue(payload) {
    switch (payload) {
        case "A":
            return 0;
        case "B":
            return 1;
        case "C":
            return 2;

        case "D":
            return 3;
        case "E":
            return 4;

        default:
            return 0;
    }
}

//? addevent-listeners-to-updated-divs
// Event-deligation

// form-data-appends
// loop-through-fields-and-append-key-value-pair
export const formData = (payloadobject) => {
    const forms = new FormData();
    for (const [key, val] of Object.entries(payloadobject)) {
        forms.append(key, val);
    }
    return forms;
};

// conert-is-to-time
export const formatTime = (isoString) => {
    const date = new Date(isoString);
    const options = { hour: "2-digit", minute: "2-digit", hour12: true };
    return date.toLocaleTimeString("en-GB", options);
};
