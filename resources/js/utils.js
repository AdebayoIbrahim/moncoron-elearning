// conver-blob-files-to-required-format-for-postrequet
export const convertBlobtofile = async (payload, filetype) => {
    const blobrequest = await fetch(payload);
    const newFile = blobrequest.blob();
    // check-file-type-for-saviing-format
    switch (filetype) {
        case "audio":
            break;
        case "video":
            break;
        case "image":
            break;

        default:
            throw new Error(`Invalid File Type `);
    }
};
