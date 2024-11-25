package demo;

import java.io.IOException;
import java.io.PrintWriter;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

@WebServlet("/BookServlet")
public class Demo extends HttpServlet {
    private static final long serialVersionUID = 1L;

    protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
        response.setContentType("text/html");
        PrintWriter out = response.getWriter();

        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
            String DB_URL = "jdbc:mysql://localhost:3306/library";
            Connection conn = DriverManager.getConnection(DB_URL, "root", "");

            // Updated SQL query to include AccNo
            PreparedStatement stmt = conn.prepareStatement("INSERT INTO book(accno, title, author, publisher, edition, price) VALUES (?, ?, ?, ?, ?, ?)");
            stmt.setString(1, request.getParameter("accno")); // AccNo
            stmt.setString(2, request.getParameter("book_name")); // Title
            stmt.setString(3, request.getParameter("author")); // Author
            stmt.setString(4, request.getParameter("publisher")); // Publisher
            stmt.setString(5, request.getParameter("edition")); // Edition
            stmt.setString(6, request.getParameter("price")); // Price

            int res = stmt.executeUpdate();

            if (res != 0) {
                out.println("Book information inserted successfully!");
            } else {
                out.println("Book information insertion failed.");
            }

            stmt.close();
            conn.close();
        } catch (Exception e) {
            out.println(e);
        }
    }
}
