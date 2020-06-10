import React, { useState, useEffect } from 'react';

function OptionText(props){

    let initItems = props.option ? JSON.parse(props.option) : [{ title: '', need_text: false}];

    const [items, setItems] = useState(initItems);

    useEffect(() => {
        props.update(JSON.stringify(items));
    }, [items]);

    const changeTitle = (e) => {
        let index = e.target.getAttribute('index');
        items[index].title = e.target.value;
        setItems([ ...items ]);
    }

    const changeNeedText = (e) => {
        let index = e.target.getAttribute('index');
        items[index].need_text = e.target.checked;
        setItems([ ...items ]);
    }

    const deleteItem = (e) => {
        let index = e.target.getAttribute('index');
        items.splice(index, 1);
        setItems(items.length == 0 ? [] : [...items]);
    }

    const addItem = () => {
        setItems([ ...items, { title: '', need_text: false}]);
    }

    return (
        <>
            <table className="table">
                <tbody>
                    <tr>
                        <th width="15%">选项标题</th>
                        <th width="15%">是否附加文本</th>
                        <th>操作</th>
                    </tr>
                    {items.map((item, index) => {
                        return <tr key={index}>
                            <td className="sub_item_text"><input index={index} type="text" className="form-control input-sm" value={item.title} onChange={changeTitle} /></td>
                            <td className="sub_item_select">
                                <input index={index} type="checkbox" className="form-control input-sm" checked={item.need_text} onChange={changeNeedText}/>
                            </td>
                            <td>
                                <button index={index} type="button" className="btn btn-danger btn-sm" onClick={deleteItem}>删除</button>
                            </td>
                        </tr>
                    })}

                    <tr>
                        <td colSpan="3" className="text-right">
                            <span className="pull-left tip text-danger"></span>
                            <button type="button" className="btn btn-sm btn-default" onClick={addItem}>添加新选项</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </>
    );
}

export default OptionText;