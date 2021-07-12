import React from "react";
import _ from 'lodash';

const Pagination = props => {
    const { currentPage, onPageChange, pages } = props;
    
    const pagesCount = pages.last_page;

    if(pagesCount == 1 || pagesCount == null) return null;
    const page = _.range(1, pagesCount + 1);

    return (
        <nav aria-label="Page navigation example">
            <ul className="pagination">
                <li className={ pages.prev_page_url == null ? "page-item disabled" : "page-item" }>
                    <a className="page-link" onClick={() => onPageChange(currentPage, "previous")} style={{ cursor: "pointer" }}>
                        <span aria-hidden="true">&laquo; Previous</span>
                        <span className="sr-only">Previous</span>
                    </a>
                </li>
                {page.map(p => (
                    <li key={p} className={ p === currentPage ? 'page-item active' : 'page-item' }>
                        <a className="page-link" onClick={() => onPageChange(p)} style={{ cursor: "pointer" }}>{p}</a>
                    </li>
                ))}
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