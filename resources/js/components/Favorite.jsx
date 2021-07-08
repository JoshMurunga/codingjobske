import React from "react";
import "bootstrap-icons/font/bootstrap-icons.css"

const Favorite = props => {
    const { count, isFavorited, onClick, isAuthorized } = props;

    let classes = "mr-1 bi bi-heart";
    if (isFavorited) classes += "-fill colored";

    if(isAuthorized!=='1') return null;

    return (
        <div>
            <i className={classes} style={{ cursor: "pointer", fontSize: "15px"}} onClick={onClick}></i> {count}
        </div>
    );
}

export default Favorite;