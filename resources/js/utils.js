// conver-blob-files-to-required-format-for-postrequet
export const convertBlobtofile = async (payload, filetype, cor) => {
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
            throw new Error(`Invalid File Type `);
    }

    const returnedFile = new File([blobfile], `${filename}`, {
        type: blobfile.type,
    });
    console.log(blobfile);
    console.log(returnedFile);

    return returnedFile;
};
