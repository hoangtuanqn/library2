<?php
function searchBooks($conn, $query) {
    $sql = "SELECT * FROM books 
            WHERE title LIKE '%$query%' 
            OR author LIKE '%$query%' 
            OR genre LIKE '%$query%'";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}


function getBooks($conn, $filter = [], $sort = 'title', $order = 'ASC', $limit = null) {
    $sql = "SELECT * FROM books";
    $where = [];
    if (!empty($filter['genre'])) {
        $where[] = "genre = '{$filter['genre']}'";
    }
    if (!empty($filter['author'])) {
        $where[] = "author = '{$filter['author']}'";
    }
    if (!empty($filter['year'])) {
        $where[] = "published_year = '{$filter['year']}'";
    }
    if (!empty($where)) {
        $sql .= " WHERE " . implode(' AND ', $where);
    }
    $sql .= " ORDER BY $sort $order";
    if ($limit) {
        $sql .= " LIMIT $limit";
    }
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getBookDetail($conn, $book_id) {
    $sql = "SELECT * FROM books WHERE id = $book_id";
    $result = $conn->query($sql);
    return $result->fetch_assoc();
}

function getBorrowedBooks($conn, $user_id) {
    $sql = "SELECT b.*, br.borrow_date, br.return_date, br.status 
            FROM borrows br 
            JOIN books b ON br.book_id = b.id 
            WHERE br.user_id = $user_id";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}
?>