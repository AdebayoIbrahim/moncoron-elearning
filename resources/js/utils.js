// conver-blob-files-to-required-format-for-postrequet
export const convertBlobtofile = async (payload, filetype, cor) => {
    const blobrequest = await fetch(payload);
    const blobfile = blobrequest.blob();

    let filename, fileformat;
    // check-file-type-for-saviing-format
    switch (filetype) {
        case "audio":
            filename = `audio_${cor}_${new Date().now}`;
            fileformat = `.mp3`;
            break;
        case "video":
            filename = `video_${cor}_${new Date().now}`;
            fileformat = `.mp4`;
            break;
        case "image":
            filename = `image_${cor}_${new Date().now}`;
            fileformat = `.png`;

            break;

        default:
            throw new Error(`Invalid File Type `);
    }

    const returnedFile = new File([blobfile], `${filename}${fileformat}`, {
        type: blobfile.type,
    });

    return returnedFile;
};
