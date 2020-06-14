import React, { useState } from 'react';

function Text(props){

    let initPlaceHolder = '';
    let initMinLimit = '';

    if(props.dataSource){
        let drJson = JSON.parse(props.dataSource);
        initPlaceHolder = drJson.placeholder;
        initMinLimit = drJson.other_limit.min_limit;
    }

    const [placeHolder, setPlaceHolder] = useState(initPlaceHolder);
    const [minLimit, setMinLimit] = useState(initMinLimit);

    const changePlaceHolder = (e) => {
        setPlaceHolder(e.target.value);
    }

    const changeMinLimit = (e) => {
        setMinLimit(e.target.value);
    }

    return (
        <>
            <div className="form-group item_options ">
                <label className="left control-label">框内提示：</label>
                <div className="right">
                    <input type='text' className="form-control input text" name="placeholder" value={placeHolder} onChange={changePlaceHolder} />
                </div>
            </div>
            <div className="form-group item_min_limit ">
                <label className="left control-label">最小字数限制：</label>
                <div className="right">
                    <input type="text" className="form-control input text" name="min_limit" value={minLimit} onChange={changeMinLimit} />
                </div>
            </div>
        </>
    );
}

export default Text;