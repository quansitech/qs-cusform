import React, { useState } from 'react';

function Text(props){

    let initPlaceHolder = '';

    if(props.dataSource){
        let drJson = JSON.parse(props.dataSource);
        initPlaceHolder = drJson.placeholder;
    }

    const [placeHolder, setPlaceHolder] = useState(initPlaceHolder);

    const changePlaceHolder = (e) => {
        setPlaceHolder(e.target.value);
    }

    return (
        <div className="form-group item_options ">
            <label className="left control-label">框内提示：</label>
            <div className="right">
                <input type='text' className="form-control input text" name="placeholder" value={placeHolder} onChange={changePlaceHolder} />
            </div>
        </div>
    );
}

export default Text;