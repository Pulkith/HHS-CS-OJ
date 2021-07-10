import java.util.*;
import java.io.*;
public class solution_ {
    public static void main(String[] args) throws IOException {
        Reader in = new Reader();
        PrintWriter out = new PrintWriter(System.out);
        
        int N = in.nextInt(), Q = in.nextInt();
        long[] tree = new long[N * 2];
        for (int i = 0; i < N; i++) {
            tree[N + i] = in.nextInt();
        }
        for (int q = 0; q < Q; q++) {
            if (in.nextInt() == 1) {
                int l = in.nextInt() - 1 + N, r = in.nextInt() - 1 + N, k = in.nextInt();
                while (l <= r) {
                    if (l % 2 == 1) {
                        tree[l] += k;
                        l >>= 1;
                        l++;
                    } else {
                        l >>= 1;
                    }
                    if (r % 2 == 0) {
                        tree[r] += k;
                        r >>= 1;
                        r--;
                    } else {
                        r >>= 1;
                    }
                }
            } else {
                int current = in.nextInt() - 1 + N;
                long res = 0;
                while (current > 0) {
                    res += tree[current];
                    current >>= 1;
                }
                out.println(res);
            }
        }
        
        out.close();
    }
    static class Reader {
        BufferedReader in;
        StringTokenizer st;
        public Reader() {
            in = new BufferedReader(new InputStreamReader(System.in));
            st = new StringTokenizer("");
        }
        public String nextLine() throws IOException {
            st = new StringTokenizer("");
            return in.readLine();
        }
        public String next() throws IOException {
            while (!st.hasMoreTokens()) {
                st = new StringTokenizer(in.readLine());
            }
            return st.nextToken();
        }
        public int nextInt() throws IOException {
            return Integer.parseInt(next());
        }
        public long nextLong() throws IOException {
            return Long.parseLong(next());
        }
    }
    public static void sort(int[] arr) {
        List<Integer> list = new ArrayList<>();
        for (int i : arr) {
            list.add(i);
        }
        Collections.sort(list);
        for (int i = 0; i < arr.length; i++) {
            arr[i] = list.get(i);
        }
    }
}