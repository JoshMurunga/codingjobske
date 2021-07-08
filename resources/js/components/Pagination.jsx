import React from "react";
import _ from 'lodash';

const Pagination = props => {
    const {itemsCount, pageSize, currentPage, onPageChange, pages} = props;
    
    // const pagesCount = Math.ceil(itemsCount / pageSize);

    // if(pagesCount === 1) return null;
    // const pages = _.range(1, pagesCount + 1);

    return (
        // <nav>
        //     <ul className="pagination">
        //         {pages.map(page => (
        //             <li key={page} className={ page === currentPage ? 'page-item active' : 'page-item' }>
        //                 <a onClick={() => onPageChange(page)} className="page-link">{page}</a>
        //             </li>
        //         ))}
        //     </ul>
        // </nav>

        // "page-item disabled"
        
        <nav aria-label="Page navigation example">
            <ul className="pagination">
                <li className={ pages.prev_page_url == null ? "page-item disabled" : "page-item" }>
                    <a className="page-link" onClick={() => onPageChange(currentPage, "previous")} style={{ cursor: "pointer" }}>
                        <span aria-hidden="true">&laquo; Previous</span>
                        <span className="sr-only">Previous</span>
                    </a>
                </li>
                <li className="page-item"><a className="page-link" href="#">1</a></li>
                <li className="page-item"><a className="page-link" href="#">2</a></li>
                <li className="page-item"><a className="page-link" href="#">3</a></li>
                <li className={ pages.next_page_url == null ? "page-item disabled" : "page-item" }>
                    <a className="page-link" onClick={() => onPageChange(currentPage, "next")} style={{ cursor: "pointer" }}>
                        <span aria-hidden="true">Next &raquo;</span>
                        <span className="sr-only">Next</span>
                    </a>
                </li>
            </ul>
        </nav>
    );
};

export default Pagination