def solution(A):
    # write your code in Python 3.6
    A = list(set(A))

    for x in A :
        if 1 not in A:
            return 1
        if x > 0 and (x - 1) > 0 :
            if x not in A:
                return x
            if (x + 1) not in A and (x - 1) not in A:
                return min([x+1, x-1])
            if (x + 1) not in A:
                return x + 1
            if (x - 1) not in A:
                return x - 1

    return 1
