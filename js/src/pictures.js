import React, { useState, useEffect } from 'react';

function Pictures(props){

    //0表示没有上传限制
    let initOption = { max_upload_count: 0};

    if(props.dataSource){
        let drJson = JSON.parse(props.dataSource);
        if(drJson.options){
            initOption = drJson.options;
        }
    }

    const [option, setOption] = useState(initOption);
    const [jsonOption, setJsonOption] = useState(JSON.stringify(initOption));

    useEffect(() => {
        setJsonOption(JSON.stringify(option));
    }, [option]);

    const changeMaxUploadCount = (e) => {
        let maxUploadCount = e.target.value;
        setOption({ max_upload_count: maxUploadCount });
    }

    return (
        <div className="form-group item_options ">
            <label className="left control-label">上传数量限制：</label>
            <div className="right">
                <input type="hidden" name="options" value={jsonOption} />
                <input type='text' className="form-control input text" value={option.max_upload_count} onChange={changeMaxUploadCount} />
                <span className="check-tips small">0表示没有上传数量限制</span>
            </div>
        </div>
    );
}

export default Pictures;