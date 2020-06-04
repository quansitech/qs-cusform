import React, { useState, useEffect } from 'react';

function Input(props){
    const [option, setOption] = useState(props.option);

    useEffect(() => {
        props.update(option);
    }, [option]);

    const changeOption = (e) => {
        setOption(e.target.value);
    }

    return (
        <>
            <input type='text' className="form-control input text" value={option} onChange={changeOption} />
            <span className="check-tips small">格式为xxx,xxx,xxx</span>
        </>
    );
}

export default Input;