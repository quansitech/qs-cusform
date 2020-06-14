import React, { useState, useEffect } from 'react';

function Input(props){

    let inputValue = '';

    if(props.dataSource){
        let drJson = JSON.parse(props.dataSource);
        inputValue = drJson.options;
    }

    const [option, setOption] = useState(inputValue);

    const changeOption = (e) => {
        setOption(e.target.value);
    }

    return (

        <div className="form-group item_options ">
            <label className="left control-label">选项：</label>
            <div className="right">
                <input type="hidden" name="options" value={option} />
                <input type='text' className="form-control input text" value={option} onChange={changeOption} />
                <span className="check-tips small">格式为xxx,xxx,xxx</span>
            </div>
        </div>
    );
}

export default Input;